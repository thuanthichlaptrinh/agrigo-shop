<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Support\Cart\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
        $this->middleware('auth')->only('add');
    }

    public function index()
    {
        return view('user.cart.index', [
            'cartItems' => $this->cart->items(),
            'cartTotal' => $this->cart->total(),
            'cartCount' => $this->cart->count(),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:SanPham,ID'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = SanPham::where('TrangThai', 1)->findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;
        $this->cart->addProduct($product, $quantity);

        return $this->cartResponse($request, 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cart->updateQuantity($data['product_id'], $data['quantity']);

        return $this->cartResponse($request, 'Cập nhật số lượng thành công.');
    }

    public function remove(Request $request, int $id)
    {
        $this->cart->remove($id);
        return $this->cartResponse($request, 'Đã xóa sản phẩm khỏi giỏ.');
    }

    public function clear(Request $request)
    {
        $this->cart->clear();
        return $this->cartResponse($request, 'Đã làm trống giỏ hàng.');
    }

    public function reorder(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để sử dụng tính năng này.');
        }

        $lastOrder = \App\Models\DonHang::where('IDNguoiDung', $user->ID)
            ->orderByDesc('NgayDat')
            ->with('chiTiet.sanPham')
            ->first();

        if (!$lastOrder) {
            return redirect()->back()->with('error', 'Bạn chưa có đơn hàng nào.');
        }

        $count = 0;
        foreach ($lastOrder->chiTiet as $detail) {
            if ($detail->sanPham && $detail->sanPham->TrangThai == 1) {
                $this->cart->addProduct($detail->sanPham, $detail->SoLuong);
                $count++;
            }
        }

        if ($count > 0) {
            return redirect()->route('user.cart.index')->with('success', 'Đã thêm sản phẩm từ đơn hàng cũ vào giỏ.');
        }

        return redirect()->back()->with('error', 'Không thể thêm sản phẩm (Sản phẩm có thể đã ngừng kinh doanh).');
    }

    protected function cartResponse(Request $request, string $message, string $type = 'success')
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'type' => $type,
                'items' => $this->cart->items(),
                'total' => $this->cart->total(),
                'count' => $this->cart->count(),
            ]);
        }

        return redirect()->back()->with([
            'status' => $message,
            'status_type' => $type,
        ]);
    }
}
