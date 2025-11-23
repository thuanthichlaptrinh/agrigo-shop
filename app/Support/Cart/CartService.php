<?php

namespace App\Support\Cart;

use App\Models\SanPham;

class CartService
{
    protected string $sessionKey = 'cart.items';

    public function items(): array
    {
        return array_values(array_map(function (array $item) {
            $item['line_total'] = (float) $item['price'] * (int) $item['quantity'];
            return $item;
        }, $this->getStoredItems()));
    }

    public function total(): float
    {
        return collect($this->items())->sum('line_total');
    }

    public function count(): int
    {
        return collect($this->getStoredItems())->sum('quantity');
    }

    public function addProduct(SanPham $product, int $quantity = 1): void
    {
        $items = $this->getStoredItems();
        $productId = (string) $product->ID;
        $quantity = max(1, $quantity);

        $itemData = $this->mapProduct($product, $quantity);

        if (isset($items[$productId])) {
            $itemData['quantity'] = $items[$productId]['quantity'] + $quantity;
        }

        $items[$productId] = $itemData;

        $this->storeItems($items);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $quantity = max(1, $quantity);
        $items = $this->getStoredItems();
        $key = (string) $productId;

        if (isset($items[$key])) {
            $items[$key]['quantity'] = $quantity;
            $this->storeItems($items);
        }
    }

    public function remove(int $productId): void
    {
        $items = $this->getStoredItems();
        unset($items[(string) $productId]);
        $this->storeItems($items);
    }

    public function clear(): void
    {
        $this->storeItems([]);
    }

    protected function getStoredItems(): array
    {
        return session($this->sessionKey, []);
    }

    protected function storeItems(array $items): void
    {
        session([$this->sessionKey => $items]);
        session(['cart_count' => $this->countFromItems($items)]);
    }

    protected function countFromItems(array $items): int
    {
        return collect($items)->sum('quantity');
    }

    protected function mapProduct(SanPham $product, int $quantity = 1): array
    {
        $promotion = $product->khuyenMai()
            ->where('KhuyenMai.TrangThai', 1)
            ->where('KhuyenMai.NgayBatDau', '<=', now())
            ->where('KhuyenMai.NgayKetThuc', '>=', now())
            ->first();

        $pricing = $promotion ? calculate_promotion_pricing($product, $promotion) : null;

        return [
            'id' => $product->ID,
            'name' => $product->TenSanPham,
            'unit' => $product->DonViTinh ?? 'Gói',
            'image' => $product->HinhAnh,
            'price' => $pricing['final_price'] ?? (float) ($product->Gia ?? 0),
            'original_price' => (float) ($product->Gia ?? 0),
            'has_discount' => (bool) $pricing,
            'discount_percent' => $pricing['discount_percent'] ?? 0,
            'quantity' => $quantity,
        ];
    }
}
