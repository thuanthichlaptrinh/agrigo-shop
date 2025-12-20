<?php

namespace App\Services;

use App\Models\DonHang;
use App\Models\SanPham;
use App\Models\Voucher;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository
    ) {}

    public function getOrders(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->getWithFilters($filters, $perPage);
    }

    public function getOrderStats(): array
    {
        $stats = $this->orderRepository->getOrderStats();
        $stats['revenue'] = $this->orderRepository->getRevenueByStatus(['Đã giao']);
        return $stats;
    }

    public function findById(int $id): ?DonHang
    {
        return $this->orderRepository
            ->with(['nguoiDung', 'voucher', 'chiTiet.sanPham'])
            ->find($id);
    }

    public function getUserOrders(int $userId): LengthAwarePaginator
    {
        return $this->orderRepository->getByUser($userId);
    }

    public function create(array $data, array $products): DonHang
    {
        return DB::transaction(function () use ($data, $products) {
            // Tạo mã đơn hàng
            $orderCode = $this->generateOrderCode();

            // Tính tổng tiền và chuẩn bị chi tiết
            $productDetails = [];
            $tongTienHang = 0;

            foreach ($products as $item) {
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
            $giamVoucher = $this->calculateVoucherDiscount($data['IDVoucher'] ?? null, $tongTienHang);

            // Tính tổng thanh toán
            $phiVanChuyen = $data['PhiVanChuyen'] ?? 0;
            $tongThanhToan = $tongTienHang + $phiVanChuyen - $giamVoucher;

            // Tạo đơn hàng
            $order = $this->orderRepository->create([
                'MaDonHang' => $orderCode,
                'IDNguoiDung' => $data['IDNguoiDung'],
                'TenNguoiNhan' => $data['TenNguoiNhan'],
                'SDT' => $data['SDT'],
                'DiaChi' => $data['DiaChi'],
                'PhuongThucTT' => $data['PhuongThucTT'],
                'PhiVanChuyen' => $phiVanChuyen,
                'GiamVoucher' => $giamVoucher,
                'IDVoucher' => $data['IDVoucher'] ?? null,
                'TongThanhToan' => $tongThanhToan,
                'TrangThai' => $data['TrangThai'] ?? 'Chờ xác nhận',
                'GhiChu' => $data['GhiChu'] ?? null,
            ]);

            // Tạo chi tiết đơn hàng và trừ kho
            foreach ($productDetails as $detail) {
                $order->chiTiet()->create($detail);
                $this->productRepository->decrementStock($detail['IDSanPham'], $detail['SoLuong']);
            }

            return $order;
        });
    }

    public function update(int $id, array $data): bool
    {
        return $this->orderRepository->update($id, $data);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $order = $this->orderRepository->findOrFail($id);
        
        // Validate trạng thái hợp lệ
        $validTransitions = [
            'Chờ xác nhận' => ['Đã xác nhận', 'Đang giao', 'Đã hủy'],
            'Đã xác nhận' => ['Đang giao', 'Đã hủy'],
            'Đang giao' => ['Đã giao', 'Đã hủy'],
            'Đã giao' => [],
            'Đã hủy' => [],
        ];

        $currentStatus = $order->TrangThai;
        if (!in_array($status, $validTransitions[$currentStatus] ?? [])) {
            return false;
        }

        return $this->orderRepository->update($id, ['TrangThai' => $status]);
    }

    public function cancel(int $id, ?string $reason = null): bool
    {
        return DB::transaction(function () use ($id, $reason) {
            $order = $this->orderRepository->with(['chiTiet'])->findOrFail($id);

            if (in_array($order->TrangThai, ['Đã giao', 'Đã hủy'])) {
                return false;
            }

            // Hoàn lại số lượng tồn kho
            foreach ($order->chiTiet as $detail) {
                $this->productRepository->incrementStock($detail->IDSanPham, $detail->SoLuong);
            }

            // Cập nhật trạng thái
            $ghiChu = $order->GhiChu ?? '';
            if ($reason) {
                $ghiChu .= "\n[Lý do hủy: {$reason}]";
            }

            return $this->orderRepository->update($id, [
                'TrangThai' => 'Đã hủy',
                'GhiChu' => $ghiChu,
            ]);
        });
    }

    public function delete(int $id): bool
    {
        $order = $this->orderRepository->with(['chiTiet'])->findOrFail($id);
        
        // Xóa chi tiết đơn hàng
        $order->chiTiet()->delete();
        
        return $this->orderRepository->delete($id);
    }

    protected function generateOrderCode(): string
    {
        return 'DH' . now()->format('YmdHis') . strtoupper(Str::random(4));
    }

    protected function calculateVoucherDiscount(?int $voucherId, float $subtotal): float
    {
        if (!$voucherId) return 0;

        $voucher = Voucher::find($voucherId);
        if (!$voucher) return 0;

        if ($voucher->Loai === 'Phần trăm') {
            $discount = ($subtotal * $voucher->GiaTri) / 100;
            if ($voucher->GiamToiDa && $discount > $voucher->GiamToiDa) {
                $discount = $voucher->GiamToiDa;
            }
            return $discount;
        }

        return (float) $voucher->GiaTri;
    }
}
