<?php

namespace App\Services;

use App\Models\SanPham;
use App\Models\HinhAnhSanPham;
use App\Repositories\ProductRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        protected ProductRepository $productRepository
    ) {}

    public function getProducts(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->productRepository->getWithFilters($filters, $perPage);
    }

    public function getProductStats(): array
    {
        return $this->productRepository->getProductStats();
    }

    public function findById(int $id): ?SanPham
    {
        return $this->productRepository
            ->with(['loaiSanPham', 'nhaCungCap', 'hinhAnh'])
            ->find($id);
    }

    public function create(array $data, ?UploadedFile $image = null, array $gallery = []): SanPham
    {
        // Upload ảnh chính
        if ($image) {
            $data['HinhAnh'] = $this->uploadImage($image);
        }

        // Tạo sản phẩm
        $product = $this->productRepository->create($data);

        // Tạo ảnh chính trong bảng HinhAnhSanPham
        if (!empty($data['HinhAnh'])) {
            $product->hinhAnh()->create([
                'DuongDan' => $data['HinhAnh'],
                'LaChinh' => true,
            ]);
        }

        // Upload gallery
        $this->syncGallery($product, $gallery);

        return $product;
    }

    public function update(int $id, array $data, ?UploadedFile $image = null, array $gallery = [], array $deleteImages = []): bool
    {
        $product = $this->productRepository->with(['hinhAnh'])->findOrFail($id);

        // Upload ảnh mới nếu có
        if ($image) {
            $data['HinhAnh'] = $this->uploadImage($image, $product->HinhAnh);
            
            // Cập nhật ảnh chính trong bảng HinhAnhSanPham
            $mainImage = $product->hinhAnh()->where('LaChinh', 1)->first();
            if ($mainImage) {
                $mainImage->update(['DuongDan' => $data['HinhAnh']]);
            } else {
                $product->hinhAnh()->create([
                    'DuongDan' => $data['HinhAnh'],
                    'LaChinh' => true,
                ]);
            }
        }

        // Xóa ảnh gallery được chọn
        $this->deleteGalleryImages($product, $deleteImages);

        // Thêm ảnh gallery mới
        $this->syncGallery($product, $gallery);

        return $this->productRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $product = $this->productRepository->with(['hinhAnh'])->findOrFail($id);

        // Xóa ảnh chính
        if ($product->HinhAnh) {
            $this->deleteImage($product->HinhAnh);
        }

        // Xóa tất cả ảnh gallery
        foreach ($product->hinhAnh as $image) {
            $this->deleteImage($image->DuongDan);
            $image->delete();
        }

        return $this->productRepository->delete($id);
    }

    public function decrementStock(int $productId, int $quantity): bool
    {
        return $this->productRepository->decrementStock($productId, $quantity);
    }

    public function incrementStock(int $productId, int $quantity): bool
    {
        return $this->productRepository->incrementStock($productId, $quantity);
    }

    protected function syncGallery(SanPham $product, array $gallery): void
    {
        foreach ($gallery as $file) {
            if (!$file instanceof UploadedFile) continue;
            
            $path = $this->uploadImage($file);
            $product->hinhAnh()->create([
                'DuongDan' => $path,
                'LaChinh' => false,
            ]);
        }
    }

    protected function deleteGalleryImages(SanPham $product, array $imageIds): void
    {
        if (empty($imageIds)) return;

        $images = HinhAnhSanPham::whereIn('ID', $imageIds)
            ->where('IDSanPham', $product->ID)
            ->where('LaChinh', false)
            ->get();

        foreach ($images as $image) {
            $this->deleteImage($image->DuongDan);
            $image->delete();
        }
    }

    protected function uploadImage(UploadedFile $file, ?string $oldPath = null): string
    {
        // Xóa ảnh cũ nếu có
        if ($oldPath) {
            $this->deleteImage($oldPath);
        }

        $filename = 'product_' . now()->format('YmdHis') . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        
        // Lưu vào storage/app/public/products
        $path = $file->storeAs('products', $filename, 'public');

        // Trả về đường dẫn để lưu vào database: storage/products/filename.jpg
        return 'storage/' . $path;
    }

    protected function deleteImage(?string $path): void
    {
        if (!$path) return;
        if (Str::startsWith($path, ['http://', 'https://'])) return;

        // Xử lý đường dẫn storage/...
        if (Str::startsWith($path, 'storage/')) {
            $storagePath = Str::after($path, 'storage/');
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
            return;
        }

        // Xử lý đường dẫn cũ uploads/... (backward compatibility)
        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
