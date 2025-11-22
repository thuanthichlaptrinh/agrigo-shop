<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use App\Models\NguoiDung;
use App\Models\SanPham;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DonHang::with(['nguoiDung', 'voucher'])
            ->withCount('chiTiet');

        // Tìm kiếm
        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('MaDonHang', 'LIKE', "%{$search}%")
                    ->orWhere('TenNguoiNhan', 'LIKE', "%{$search}%")
                    ->orWhere('SDT', 'LIKE', "%{$search}%")
                    ->orWhere('DiaChi', 'LIKE', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('TrangThai', $request->input('status'));
        }

        // Lọc theo phương thức thanh toán
        if ($request->filled('payment_method')) {
            $query->where('PhuongThucTT', $request->input('payment_method'));
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_total')) {
            $query->where('TongThanhToan', '>=', (float) $request->min_total);
        }

        if ($request->filled('max_total')) {
            $query->where('TongThanhToan', '<=', (float) $request->max_total);
        }

        // Lọc theo ngày đặt
        if ($request->filled('date_from')) {
            $query->whereDate('NgayDat', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('NgayDat', '<=', $request->date_to);
        }

        // Sắp xếp
        $sortBy = $request->input('sort_by', 'NgayDat');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSorts = ['NgayDat', 'TongThanhToan', 'MaDonHang'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'NgayDat';
        }
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }
        $query->orderBy($sortBy, $sortDirection);

        // Phân trang
        $perPageOptions = [10, 20, 30, 50];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $orders = $query->paginate($perPage)->withQueryString();

        // Thống kê
        $stats = [
            'total' => DonHang::count(),
            'pending' => DonHang::where('TrangThai', 'Chờ xác nhận')->count(),
            'confirmed' => DonHang::where('TrangThai', 'Đã xác nhận')->count(),
            'shipping' => DonHang::where('TrangThai', 'Đang giao')->count(),
            'completed' => DonHang::where('TrangThai', 'Đã giao')->count(),
            'cancelled' => DonHang::where('TrangThai', 'Đã hủy')->count(),
            'revenue' => DonHang::whereIn('TrangThai', ['Đã giao'])->sum('TongThanhToan'),
        ];

        $statusOptions = [
            'Chờ xác nhận',
            'Đã xác nhận',
            'Đang giao',
            'Đã giao',
            'Đã hủy'
        ];

        $paymentMethods = [
            'COD' => 'Thanh toán khi nhận hàng',
            'VNPay' => 'VNPay',
            'MoMo' => 'MoMo',
            'ZaloPay' => 'ZaloPay',
        ];

        $customers = NguoiDung::where('IDVaiTro', '!=', 1)->orderBy('TenNguoiDung')->get(['ID', 'TenNguoiDung', 'Email']);
        $products = SanPham::where('TrangThai', 1)->orderBy('TenSanPham')->get(['ID', 'TenSanPham', 'Gia', 'SoLuongTon', 'DonViTinh']);
        $vouchers = Voucher::where('NgayKetThuc', '>=', now())
            ->orderBy('MaVoucher')
            ->get(['ID', 'MaVoucher', 'GiaTri', 'Loai', 'GiamToiDa']);

        return view('admin.orders.index', compact(
            'orders',
            'stats',
            'statusOptions',
            'paymentMethods',
            'customers',
            'products',
            'vouchers',
            'perPageOptions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'IDNguoiDung' => ['required', 'exists:NguoiDung,ID'],
            'TenNguoiNhan' => ['required', 'string', 'max:255'],
            'SDT' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
            'DiaChi' => ['required', 'string', 'max:500'],
            'PhuongThucTT' => ['required', 'string'],
            'PhiVanChuyen' => ['required', 'numeric', 'min:0'],
            'IDVoucher' => ['nullable', 'exists:Voucher,ID'],
            'GhiChu' => ['nullable', 'string', 'max:1000'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.IDSanPham' => ['required', 'exists:SanPham,ID'],
            'products.*.SoLuong' => ['required', 'integer', 'min:1'],
        ], [
            'IDNguoiDung.required' => 'Vui lòng chọn khách hàng',
            'products.required' => 'Vui lòng thêm ít nhất 1 sản phẩm',
        ]);

        // Tạo mã đơn hàng
        $maDonHang = 'DH' . now()->format('YmdHis') . strtoupper(Str::random(4));

        // Tính tổng tiền
        $tongTienHang = 0;
        $productDetails = [];

        foreach ($validated['products'] as $item) {
            $product = SanPham::find($item['IDSanPham']);
            if (!$product) continue;

            $donGia = $product->Gia;
            $soLuong = $item['SoLuong'];
            $thanhTien = $donGia * $soLuong;
            $tongTienHang += $thanhTien;

            $productDetails[] = [
                'IDSanPham' => $product->ID,
                'TenSanPham' => $product->TenSanPham,
                'SoLuong' => $soLuong,
                'DonGia' => $donGia,
            ];
        }

        // Tính giảm giá voucher
        $giamVoucher = 0;
        if ($validated['IDVoucher']) {
            $voucher = Voucher::find($validated['IDVoucher']);
            if ($voucher) {
                if ($voucher->Loai === 'Phần trăm') {
                    $giamVoucher = ($tongTienHang * $voucher->GiaTri) / 100;
                    if ($voucher->GiamToiDa && $giamVoucher > $voucher->GiamToiDa) {
                        $giamVoucher = $voucher->GiamToiDa;
                    }
                } else {
                    $giamVoucher = $voucher->GiaTri;
                }
            }
        }

        $tongThanhToan = $tongTienHang + $validated['PhiVanChuyen'] - $giamVoucher;

        // Tạo đơn hàng (Admin tạo đơn → Trạng thái 'Đang giao')
        $order = DonHang::create([
            'MaDonHang' => $maDonHang,
            'IDNguoiDung' => $validated['IDNguoiDung'],
            'TenNguoiNhan' => $validated['TenNguoiNhan'],
            'SDT' => $validated['SDT'],
            'DiaChi' => $validated['DiaChi'],
            'PhuongThucTT' => $validated['PhuongThucTT'],
            'PhiVanChuyen' => $validated['PhiVanChuyen'],
            'GiamVoucher' => $giamVoucher,
            'IDVoucher' => $validated['IDVoucher'],
            'TongThanhToan' => $tongThanhToan,
            'TrangThai' => 'Đang giao',
            'GhiChu' => $validated['GhiChu'] ?? null,
        ]);

        // Tạo chi tiết đơn hàng và trừ số lượng tồn kho
        foreach ($productDetails as $detail) {
            $order->chiTiet()->create($detail);
            
            // Trừ số lượng tồn kho
            $product = SanPham::find($detail['IDSanPham']);
            if ($product) {
                $product->decrement('SoLuongTon', $detail['SoLuong']);
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Đã tạo đơn hàng thành công!');
    }

    public function update(Request $request, $id)
    {
        $order = DonHang::findOrFail($id);

        $validated = $request->validate([
            'TenNguoiNhan' => ['required', 'string', 'max:255'],
            'SDT' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
            'DiaChi' => ['required', 'string', 'max:500'],
            'TrangThai' => ['required', 'string'],
            'GhiChu' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Đã cập nhật đơn hàng thành công!');
    }

    public function destroy($id)
    {
        $order = DonHang::with('chiTiet')->findOrFail($id);

        // Xóa chi tiết đơn hàng
        $order->chiTiet()->delete();

        // Xóa đơn hàng
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng.');
    }

    public function show($id)
    {
        $order = DonHang::with(['nguoiDung', 'voucher', 'chiTiet.sanPham'])->findOrFail($id);

        if (!request()->wantsJson()) {
            return redirect()->route('admin.orders.index');
        }

        return response()->json($order);
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'orders' => ['required', 'array', 'min:1', 'max:20'],
            'orders.*.IDNguoiDung' => ['required', 'exists:NguoiDung,ID'],
            'orders.*.TenNguoiNhan' => ['required', 'string', 'max:255'],
            'orders.*.SDT' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
            'orders.*.DiaChi' => ['required', 'string', 'max:500'],
            'orders.*.PhuongThucTT' => ['required', 'string'],
            'orders.*.PhiVanChuyen' => ['required', 'numeric', 'min:0'],
            'orders.*.IDVoucher' => ['nullable', 'exists:Voucher,ID'],
            'orders.*.TongThanhToan' => ['required', 'numeric', 'min:0'],
            'orders.*.products' => ['required', 'array', 'min:1'],
            'orders.*.products.*.IDSanPham' => ['required', 'exists:SanPham,ID'],
            'orders.*.products.*.SoLuong' => ['required', 'integer', 'min:1'],
        ], [
            'orders.required' => 'Vui lòng thêm ít nhất một đơn hàng.',
            'orders.*.products.required' => 'Vui lòng thêm sản phẩm cho đơn hàng.',
        ]);

        $created = 0;
        foreach ($validated['orders'] as $item) {
            $maDonHang = 'DH' . now()->format('YmdHis') . strtoupper(Str::random(4));

            // Tính giảm giá voucher
            $giamVoucher = 0;
            if (!empty($item['IDVoucher'])) {
                $voucher = Voucher::find($item['IDVoucher']);
                if ($voucher) {
                    $tongTienHang = collect($item['products'])->reduce(function ($total, $product) {
                        $sanPham = SanPham::find($product['IDSanPham']);
                        return $total + ($sanPham ? $sanPham->Gia * $product['SoLuong'] : 0);
                    }, 0);
                    
                    if ($voucher->Loai === 'Phần trăm') {
                        $giamVoucher = ($tongTienHang * $voucher->GiaTri) / 100;
                        if ($voucher->GiamToiDa && $giamVoucher > $voucher->GiamToiDa) {
                            $giamVoucher = $voucher->GiamToiDa;
                        }
                    } else {
                        $giamVoucher = $voucher->GiaTri;
                    }
                }
            }

            $order = DonHang::create([
                'MaDonHang' => $maDonHang,
                'IDNguoiDung' => $item['IDNguoiDung'],
                'TenNguoiNhan' => $item['TenNguoiNhan'],
                'SDT' => $item['SDT'],
                'DiaChi' => $item['DiaChi'],
                'PhuongThucTT' => $item['PhuongThucTT'],
                'PhiVanChuyen' => $item['PhiVanChuyen'],
                'GiamVoucher' => $giamVoucher,
                'IDVoucher' => $item['IDVoucher'] ?? null,
                'TongThanhToan' => $item['TongThanhToan'],
                'TrangThai' => 'Đang giao',
                'GhiChu' => $item['GhiChu'] ?? null,
            ]);

            // Tạo chi tiết đơn hàng và trừ số lượng tồn kho
            foreach ($item['products'] as $product) {
                $sanPham = SanPham::find($product['IDSanPham']);
                if ($sanPham) {
                    $order->chiTiet()->create([
                        'IDSanPham' => $sanPham->ID,
                        'TenSanPham' => $sanPham->TenSanPham,
                        'SoLuong' => $product['SoLuong'],
                        'DonGia' => $sanPham->Gia,
                    ]);
                    
                    // Trừ số lượng tồn kho
                    $sanPham->decrement('SoLuongTon', $product['SoLuong']);
                }
            }

            $created++;
        }

        return redirect()->route('admin.orders.index')->with('success', "Đã tạo {$created} đơn hàng.");
    }

    // Duyệt đơn hàng (Chờ xác nhận → Đang giao hoặc Đã giao)
    public function approve(Request $request, $id)
    {
        $order = DonHang::findOrFail($id);

        // Kiểm tra trạng thái hợp lệ
        if (!in_array($order->TrangThai, ['Chờ xác nhận', 'Đang giao'])) {
            return redirect()->route('admin.orders.index')->with('error', 'Không thể cập nhật trạng thái đơn hàng này.');
        }

        $validated = $request->validate([
            'trang_thai' => ['required', 'in:Đang giao,Đã giao'],
        ]);

        // Nếu đơn hàng đang "Đang giao" chỉ cho phép cập nhật sang "Đã giao"
        if ($order->TrangThai === 'Đang giao' && $validated['trang_thai'] !== 'Đã giao') {
            return redirect()->route('admin.orders.index')->with('error', 'Đơn hàng đang giao chỉ có thể cập nhật sang "Đã giao".');
        }

        $order->update([
            'TrangThai' => $validated['trang_thai'],
        ]);
        
        // Nếu cập nhật thành "Đã giao", trừ số lượng tồn kho
        if ($validated['trang_thai'] === 'Đã giao') {
            foreach ($order->chiTiet as $detail) {
                if ($detail->sanPham) {
                    $detail->sanPham->decrement('SoLuongTon', $detail->SoLuong);
                }
            }
        }

        $message = $order->TrangThai === 'Đã giao' ? 'Đã xác nhận giao hàng thành công!' : 'Đã duyệt đơn hàng thành công!';
        return redirect()->route('admin.orders.index')->with('success', $message);
    }

    // Hủy đơn hàng
    public function cancel(Request $request, $id)
    {
        $order = DonHang::findOrFail($id);

        if (in_array($order->TrangThai, ['Đã giao', 'Đã hủy'], true)) {
            return redirect()->route('admin.orders.index')->with('error', 'Không thể hủy đơn hàng đã giao hoặc đã hủy.');
        }

        $validated = $request->validate([
            'ly_do_huy' => ['nullable', 'string', 'max:500'],
        ]);

        $ghiChu = $order->GhiChu ?? '';
        if (!empty($validated['ly_do_huy'])) {
            $ghiChu .= "\n[Lý do hủy: {$validated['ly_do_huy']}]";
        }

        $order->update([
            'TrangThai' => 'Đã hủy',
            'GhiChu' => $ghiChu,
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Đã hủy đơn hàng.');
    }
}
