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
