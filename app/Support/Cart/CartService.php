<?php

namespace App\Support\Cart;

use App\Models\GioHang;
use App\Models\SanPham;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    protected string $sessionKey = 'cart.items';
    protected ?int $userId = null;
    protected ?Collection $databaseItemsCache = null;
    protected ?array $formattedItemsCache = null;

    public function __construct()
    {
        $this->userId = $this->resolveUserId();
    }

    public function items(): array
    {
        if ($this->usingDatabase()) {
            if ($this->formattedItemsCache !== null) {
                return $this->formattedItemsCache;
            }

            $this->formattedItemsCache = $this->databaseItems()
                ->map(fn (GioHang $entry) => $this->transformDatabaseEntry($entry))
                ->filter()
                ->values()
                ->all();

            return $this->formattedItemsCache;
        }

        return $this->formatSessionItems($this->getStoredItems());
    }

    public function total(): float
    {
        return collect($this->items())->sum('line_total');
    }

    public function count(): int
    {
        if ($this->usingDatabase()) {
            $userId = $this->getUserId();
            if ($this->formattedItemsCache !== null) {
                $count = (int) collect($this->formattedItemsCache)->sum('quantity');
            } elseif ($this->databaseItemsCache !== null) {
                $count = (int) $this->databaseItemsCache->sum('SoLuong');
            } else {
                $count = (int) GioHang::where('IDNguoiDung', $userId)->sum('SoLuong');
            }

            $this->rememberCountValue($count);
            return $count;
        }

        $count = $this->countFromItems($this->getStoredItems());
        $this->rememberCountValue($count);

        return $count;
    }

    public function addProduct(SanPham $product, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);

        if ($this->usingDatabase()) {
            $userId = $this->getUserId();
            $productId = (int) $product->ID;

            $currentQuantity = (int) DB::table('GioHang')
                ->where('IDNguoiDung', $userId)
                ->where('IDSanPham', $productId)
                ->value('SoLuong');

            DB::table('GioHang')->updateOrInsert(
                [
                    'IDNguoiDung' => $userId,
                    'IDSanPham' => $productId,
                ],
                [
                    'SoLuong' => $currentQuantity + $quantity,
                    'NgayCapNhat' => now(),
                ]
            );

            $this->forgetDatabaseCache();
            return;
        }

        $items = $this->getStoredItems();
        $productId = (string) $product->ID;

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

        if ($this->usingDatabase()) {
            $updated = DB::table('GioHang')
                ->where('IDNguoiDung', $this->getUserId())
                ->where('IDSanPham', $productId)
                ->update([
                    'SoLuong' => $quantity,
                    'NgayCapNhat' => now(),
                ]);

            if ($updated) {
                $this->forgetDatabaseCache();
            }

            return;
        }

        $items = $this->getStoredItems();
        $key = (string) $productId;

        if (isset($items[$key])) {
            $items[$key]['quantity'] = $quantity;
            $this->storeItems($items);
        }
    }

    public function remove(int $productId): void
    {
        if ($this->usingDatabase()) {
            DB::table('GioHang')
                ->where('IDNguoiDung', $this->getUserId())
                ->where('IDSanPham', $productId)
                ->delete();

            $this->forgetDatabaseCache();
            return;
        }

        $items = $this->getStoredItems();
        unset($items[(string) $productId]);
        $this->storeItems($items);
    }

    public function clear(): void
    {
        if ($this->usingDatabase()) {
            DB::table('GioHang')
                ->where('IDNguoiDung', $this->getUserId())
                ->delete();

            $this->forgetDatabaseCache();
            $this->rememberCountValue(0);
            return;
        }

        $this->storeItems([]);
    }

    protected function usingDatabase(): bool
    {
        return !empty($this->getUserId());
    }

    protected function getUserId(): ?int
    {
        if ($this->userId !== null) {
            return $this->userId;
        }

        $this->userId = $this->resolveUserId();

        return $this->userId;
    }

    protected function resolveUserId(): ?int
    {
        $authId = Auth::id();
        if ($authId) {
            return (int) $authId;
        }

        if (session()->has('user_id')) {
            return (int) session('user_id');
        }

        if (function_exists('auth_user')) {
            return optional(auth_user())->ID;
        }

        return null;
    }

    protected function databaseItems(): Collection
    {
        if ($this->databaseItemsCache !== null) {
            return $this->databaseItemsCache;
        }

        $this->databaseItemsCache = GioHang::with('sanPham')
            ->where('IDNguoiDung', $this->getUserId())
            ->get();

        return $this->databaseItemsCache;
    }

    protected function transformDatabaseEntry(GioHang $entry): ?array
    {
        if (!$entry->sanPham) {
            return null;
        }

        $item = $this->mapProduct($entry->sanPham, (int) $entry->SoLuong);
        $item['line_total'] = (float) $item['price'] * (int) $item['quantity'];

        return $item;
    }

    protected function forgetDatabaseCache(): void
    {
        $this->databaseItemsCache = null;
        $this->formattedItemsCache = null;
    }

    protected function formatSessionItems(array $items): array
    {
        return array_values(array_map(function (array $item) {
            $item['line_total'] = (float) $item['price'] * (int) $item['quantity'];
            return $item;
        }, $items));
    }

    protected function getStoredItems(): array
    {
        return session($this->sessionKey, []);
    }

    protected function storeItems(array $items): void
    {
        session([$this->sessionKey => $items]);
        $this->rememberCountValue($this->countFromItems($items));
    }

    protected function rememberCountValue(int $count): void
    {
        session(['cart_count' => $count]);
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
