<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use App\Models\ThanhToan;
use App\Models\Voucher;
use App\Support\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected CartService $cart;
    protected int $shippingFee = 20000;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $summary = session('checkout_completed');

        if (empty($summary)) {
            return redirect()->route('user.cart.index')->with('status', 'Không tìm thấy thông tin đơn hàng.');
        }

        return view('user.cart.checkout', [
            'summary' => $summary,
        ]);
    }

    public function payment()
    {
        $items = $this->cart->items();

        if (empty($items)) {
            return redirect()->route('user.cart.index')->with('status', 'Giỏ hàng hiện đang trống, vui lòng thêm sản phẩm trước.');
        }

        $authUser = function_exists('auth_user') ? auth_user() : null;
        $defaults = [
            'receiver' => $authUser->TenNguoiDung ?? null,
            'phone' => $authUser->SoDienThoai ?? null,
            'address' => $authUser->DiaChi ?? null,
        ];

        $deliverySlots = [];
        $start = Carbon::now();
        for ($offset = 0; $offset < 3; $offset++) {
            $date = (clone $start)->addDays($offset);
            $deliverySlots[] = '16h - 19h ngày ' . $date->format('d/m');
        }

        return view('user.cart.payment', [
            'cartItems' => $items,
            'cartTotal' => $this->cart->total(),
            'cartCount' => $this->cart->count(),
            'defaults' => $defaults,
            'deliverySlots' => $deliverySlots,
            'appliedVoucher' => $this->currentVoucher(),
            'shippingFeeAmount' => $this->shippingFee,
        ]);
    }

    public function applyVoucher(Request $request)
    {
        $items = $this->cart->items();

        if (empty($items)) {
            return redirect()->route('user.cart.index')->with('status', 'Giỏ hàng hiện đang trống, vui lòng thêm sản phẩm trước.');
        }

        $validated = $request->validate([
            'voucher_code' => ['required', 'string', 'max:50'],
        ], [
            'voucher_code.required' => 'Vui lòng nhập mã giảm giá.',
        ]);

        $code = strtoupper(trim($validated['voucher_code']));
        $voucher = Voucher::where('MaVoucher', $code)->first();

        if (!$voucher) {
            return $this->voucherFailedRedirect($request, 'Mã giảm giá không tồn tại hoặc đã bị thu hồi.', $code);
        }

        if (!$voucher->isAvailable()) {
            return $this->voucherFailedRedirect($request, 'Mã giảm giá đã hết lượt sử dụng hoặc hết hạn.', $code);
        }

        $subtotal = $this->cart->total();

        if (!$voucher->canApply($subtotal)) {
            $minTotal = number_format((float) ($voucher->DonToiThieu ?? 0), 0, ',', '.');
            return $this->voucherFailedRedirect($request, 'Đơn tối thiểu để áp dụng mã này là ' . $minTotal . 'đ.', $code);
        }

        $discount = (float) $voucher->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return $this->voucherFailedRedirect($request, 'Mã giảm giá chưa đủ điều kiện áp dụng cho đơn hàng hiện tại.', $code);
        }

        session(['checkout_voucher' => [
            'id' => $voucher->ID,
            'code' => $voucher->MaVoucher,
            'discount_amount' => $discount,
            'type' => $voucher->Loai,
            'min_order' => (float) ($voucher->DonToiThieu ?? 0),
            'max_discount' => (float) ($voucher->GiamToiDa ?? 0),
            'value' => (float) ($voucher->GiaTri ?? 0),
        ]]);

        $input = $request->except('_token', 'intent');
        $input['voucher_code'] = $code;

        return redirect()->route('user.checkout.payment')
            ->withInput($input)
            ->with('voucher_success', 'Đã áp dụng mã ' . $voucher->MaVoucher . ' thành công.');
    }

    public function process(Request $request)
    {
        $items = $this->cart->items();

        if (empty($items)) {
            return redirect()->route('user.cart.index')->with('status', 'Giỏ hàng hiện đang trống, vui lòng thêm sản phẩm trước.');
        }

        $validated = $request->validate([
            'receiver' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'delivery_window' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cod,bank,vnpay,momo'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'receiver.required' => 'Vui lòng nhập họ tên người nhận.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'address.required' => 'Vui lòng nhập địa chỉ nhận hàng.',
            'delivery_window.required' => 'Vui lòng chọn khung giờ giao hàng.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $paymentMethodLabel = match ($validated['payment_method']) {
            'bank' => 'Thanh toán qua thẻ ngân hàng',
            'vnpay' => 'Thanh toán VNPay QR',
            'momo' => 'Thanh toán ví MoMo',
            default => 'Thanh toán khi giao hàng (COD)',
        };

        $subtotal = $this->cart->total();
        [$voucherModel, $voucherDiscount] = $this->resolveVoucherDiscount($subtotal);
        $shippingFee = $this->shippingFee;
        $grandTotal = max(0, $subtotal + $shippingFee - $voucherDiscount);

        $user = function_exists('auth_user') ? auth_user() : null;
        $orderCode = $this->generateOrderCode();
        $paymentChannel = $this->mapPaymentChannel($validated['payment_method']);
        $paymentStatus = $paymentChannel === 'COD' ? 'Chờ' : 'Thành công';
        $paymentDate = $paymentStatus === 'Thành công' ? now() : null;

        $order = DB::transaction(function () use (
            $orderCode,
            $validated,
            $user,
            $shippingFee,
            $voucherDiscount,
            $voucherModel,
            $grandTotal,
            $items,
            $paymentChannel,
            $paymentStatus,
            $paymentDate
        ) {
            $order = DonHang::create([
                'MaDonHang' => $orderCode,
                'IDNguoiDung' => $user->ID ?? null,
                'TenNguoiNhan' => $validated['receiver'],
                'SDT' => $validated['phone'],
                'DiaChi' => $validated['address'],
                'PhuongThucTT' => $paymentChannel,
                'PhiVanChuyen' => (float) $shippingFee,
                'GiamVoucher' => (float) $voucherDiscount,
                'IDVoucher' => $voucherModel?->ID,
                'TongThanhToan' => (float) $grandTotal,
                'TrangThai' => 'Chờ xác nhận',
                'GhiChu' => $validated['note'] ?? null,
            ]);

            foreach ($items as $item) {
                ChiTietDonHang::create([
                    'IDDonHang' => $order->ID,
                    'IDSanPham' => isset($item['id']) ? (int) $item['id'] : null,
                    'TenSanPham' => $item['name'] ?? 'Sản phẩm',
                    'SoLuong' => (int) ($item['quantity'] ?? 1),
                    'DonGia' => (float) ($item['price'] ?? 0),
                ]);
            }

            ThanhToan::create([
                'IDDonHang' => $order->ID,
                'SoTien' => (float) $grandTotal,
                'PhuongThuc' => $paymentChannel,
                'TrangThai' => $paymentStatus,
                'NgayThanhToan' => $paymentDate,
            ]);

            if ($voucherModel) {
                $voucherModel->increment('DaDung');
            }

            return $order;
        });

        $summary = [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'voucher_discount' => $voucherDiscount,
            'voucher_code' => $voucherModel?->MaVoucher,
            'total' => $grandTotal,
            'count' => $this->cart->count(),
            'receiver' => $validated['receiver'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'delivery_window' => $validated['delivery_window'],
            'payment_method' => $paymentMethodLabel,
            'payment_method_code' => $paymentChannel,
            'payment_status' => $paymentStatus,
            'order_status' => $order->TrangThai ?? 'Chờ xác nhận',
            'order_code' => $orderCode,
            'order_id' => $order->ID ?? null,
            'note' => $validated['note'] ?? null,
        ];

        session(['checkout_completed' => $summary]);
        session()->forget('checkout_summary');
        $this->clearVoucher();

        $this->cart->clear();

        return redirect()->route('user.checkout.index');
    }

    public function confirm(Request $request)
    {
        $summary = session('checkout_summary');

        if (empty($summary)) {
            return redirect()->route('user.cart.index')->with('status', 'Phiên thanh toán đã hết hạn, vui lòng thực hiện lại.');
        }

        $summary['payment_method'] = $request->input('payment_method', $summary['payment_method'] ?? 'Thanh toán khi nhận hàng');
        $summary['note'] = $request->input('note', $summary['note'] ?? null);

        session(['checkout_completed' => $summary]);
        session()->forget('checkout_summary');

        return redirect()->route('user.checkout.index');
    }

    public function cancel(Request $request)
    {
        $orderId = (int) $request->input('order_id');
        $summary = session('checkout_completed');

        if (!$orderId || empty($summary) || (int) ($summary['order_id'] ?? 0) !== $orderId) {
            return redirect()->route('user.checkout.index')->with('status', 'Không tìm thấy thông tin đơn hàng để hủy.');
        }

        $order = DonHang::find($orderId);

        if (!$order) {
            return redirect()->route('user.checkout.index')->with('status', 'Đơn hàng không tồn tại hoặc đã bị xóa.');
        }

        if (!$order->canCancel()) {
            return redirect()->route('user.checkout.index')->with('status', 'Đơn hàng đã được xử lý, không thể hủy.');
        }

        DB::transaction(function () use ($order) {
            $order->TrangThai = 'Đã hủy';
            $order->GhiChu = trim(($order->GhiChu ? $order->GhiChu . PHP_EOL : '') . '[Khách yêu cầu hủy đơn từ trang xác nhận]');
            $order->NgayCapNhat = now();
            $order->save();
        });

        $summary['order_status'] = 'Đã hủy';
        session(['checkout_completed' => $summary]);

        session()->forget('checkout_completed');

        return redirect()->route('user.cart.index')->with('status', 'Đơn hàng đã được hủy thành công.');
    }

    public function edit()
    {
        $summary = session('checkout_completed');

        if (empty($summary) || empty($summary['order_id'])) {
            return redirect()->route('user.cart.index')->with('status', 'Không tìm thấy đơn hàng để chỉnh sửa.');
        }

        $order = DonHang::find($summary['order_id']);

        if (!$order) {
            return redirect()->route('user.cart.index')->with('status', 'Đơn hàng không tồn tại hoặc đã bị xóa.');
        }

        $deliverySlots = [];
        $start = Carbon::now();
        for ($offset = 0; $offset < 3; $offset++) {
            $date = (clone $start)->addDays($offset);
            $deliverySlots[] = '16h - 19h ngày ' . $date->format('d/m');
        }

        return view('user.cart.checkout-edit', [
            'order' => $order,
            'summary' => $summary,
            'deliverySlots' => $deliverySlots,
        ]);
    }

    public function update(Request $request)
    {
        $summary = session('checkout_completed');

        if (empty($summary) || empty($summary['order_id'])) {
            return redirect()->route('user.cart.index')->with('status', 'Không tìm thấy đơn hàng để chỉnh sửa.');
        }

        $order = DonHang::find($summary['order_id']);

        if (!$order || !$order->canCancel()) {
            return redirect()->route('user.checkout.index')->with('status', 'Đơn hàng đã được xử lý, không thể chỉnh sửa.');
        }

        $validated = $request->validate([
            'receiver' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'delivery_window' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cod,bank,vnpay,momo'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $channel = $this->mapPaymentChannel($validated['payment_method']);
        $paymentLabel = match ($validated['payment_method']) {
            'bank' => 'Thanh toán qua thẻ ngân hàng',
            'vnpay' => 'Thanh toán VNPay QR',
            'momo' => 'Thanh toán ví MoMo',
            default => 'Thanh toán khi giao hàng (COD)',
        };

        $order->update([
            'TenNguoiNhan' => $validated['receiver'],
            'SDT' => $validated['phone'],
            'DiaChi' => $validated['address'],
            'PhuongThucTT' => $channel,
            'GhiChu' => $validated['note'] ?? null,
        ]);

        $summary = array_merge($summary, [
            'receiver' => $validated['receiver'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'delivery_window' => $validated['delivery_window'],
            'payment_method' => $paymentLabel,
            'payment_method_code' => $validated['payment_method'],
            'note' => $validated['note'] ?? null,
        ]);
        session(['checkout_completed' => $summary]);

        return redirect()->route('user.checkout.index')->with('status', 'Đã cập nhật thông tin đơn hàng.');
    }

    protected function mapPaymentChannel(string $method): string
    {
        return match ($method) {
            'bank' => 'Bank',
            'vnpay' => 'VNPAY',
            'momo' => 'Momo',
            default => 'COD',
        };
    }

    protected function generateOrderCode(): string
    {
        return 'DH' . now()->format('YmdHis') . strtoupper(Str::random(3));
    }

    protected function resolveVoucherDiscount(float $subtotal): array
    {
        $voucherData = $this->currentVoucher();

        if (!$voucherData) {
            return [null, 0];
        }

        $voucher = null;
        if (!empty($voucherData['id'])) {
            $voucher = Voucher::find($voucherData['id']);
        }

        if (!$voucher && !empty($voucherData['code'])) {
            $voucher = Voucher::where('MaVoucher', $voucherData['code'])->first();
        }

        if (!$voucher || !$voucher->canApply($subtotal)) {
            $this->clearVoucher();
            return [null, 0];
        }

        $discount = (float) $voucher->calculateDiscount($subtotal);

        if ($discount <= 0) {
            $this->clearVoucher();
            return [null, 0];
        }

        session(['checkout_voucher' => array_merge($voucherData, [
            'id' => $voucher->ID,
            'code' => $voucher->MaVoucher,
            'discount_amount' => $discount,
        ])]);

        return [$voucher, $discount];
    }

    protected function currentVoucher(): ?array
    {
        $voucher = session('checkout_voucher');
        return is_array($voucher) ? $voucher : null;
    }

    protected function clearVoucher(): void
    {
        session()->forget('checkout_voucher');
    }

    protected function voucherFailedRedirect(Request $request, string $message, ?string $code = null)
    {
        $input = $request->except('_token', 'intent');

        if ($code !== null) {
                $voucherSnapshot = $this->currentVoucher();
            $input['voucher_code'] = $code;
        
                if ($voucherSnapshot && !$voucherModel) {
                    $input = $request->except('_token');
                    $input['voucher_code'] = $voucherSnapshot['code'] ?? ($request->input('voucher_code') ?? '');

                    return redirect()->route('user.checkout.payment')
                        ->withInput($input)
                        ->with('voucher_error', 'Mã giảm giá không còn hiệu lực, vui lòng áp dụng lại.');
                }
        }

        return redirect()->route('user.checkout.payment')
            ->withInput($input)
            ->with('voucher_error', $message);
    }
}
