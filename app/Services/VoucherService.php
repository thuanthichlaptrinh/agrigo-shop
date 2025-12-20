<?php

namespace App\Services;

use App\Models\Voucher;

class VoucherService
{
    /**
     * Tìm voucher theo mã
     */
    public function findByCode(string $code): ?Voucher
    {
        return Voucher::where('MaVoucher', strtoupper(trim($code)))->first();
    }

    /**
     * Kiểm tra voucher có thể áp dụng
     */
    public function canApply(Voucher $voucher, float $subtotal): array
    {
        if (!$voucher->isAvailable()) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng hoặc hết hạn.'
            ];
        }

        if (!$voucher->canApply($subtotal)) {
            $minTotal = number_format((float) ($voucher->DonToiThieu ?? 0), 0, ',', '.');
            return [
                'valid' => false,
                'message' => "Đơn tối thiểu để áp dụng mã này là {$minTotal}đ."
            ];
        }

        return [
            'valid' => true,
            'message' => 'Voucher hợp lệ'
        ];
    }

    /**
     * Tính giảm giá từ voucher
     */
    public function calculateDiscount(Voucher $voucher, float $subtotal): float
    {
        $discount = (float) $voucher->calculateDiscount($subtotal);
        return max(0, $discount);
    }

    /**
     * Áp dụng voucher và trả về thông tin
     */
    public function apply(string $code, float $subtotal): array
    {
        $voucher = $this->findByCode($code);

        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã bị thu hồi.'
            ];
        }

        $validation = $this->canApply($voucher, $subtotal);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message']
            ];
        }

        $discount = $this->calculateDiscount($voucher, $subtotal);

        if ($discount <= 0) {
            return [
                'success' => false,
                'message' => 'Mã giảm giá chưa đủ điều kiện áp dụng cho đơn hàng hiện tại.'
            ];
        }

        return [
            'success' => true,
            'voucher' => [
                'id' => $voucher->ID,
                'code' => $voucher->MaVoucher,
                'discount_amount' => $discount,
                'type' => $voucher->Loai,
                'min_order' => (float) ($voucher->DonToiThieu ?? 0),
                'max_discount' => (float) ($voucher->GiamToiDa ?? 0),
                'value' => (float) ($voucher->GiaTri ?? 0),
            ],
            'message' => "Đã áp dụng mã {$voucher->MaVoucher} thành công."
        ];
    }

    /**
     * Tăng số lần sử dụng voucher
     */
    public function incrementUsage(int $voucherId): void
    {
        $voucher = Voucher::find($voucherId);
        if ($voucher) {
            $voucher->increment('DaDung');
        }
    }

    /**
     * Lấy danh sách voucher còn hiệu lực
     */
    public function getActiveVouchers()
    {
        return Voucher::where('NgayKetThuc', '>=', now())
            ->orderBy('MaVoucher')
            ->get();
    }
}
