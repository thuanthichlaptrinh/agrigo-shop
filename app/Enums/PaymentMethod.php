<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'COD';
    case BANK = 'Bank';
    case VNPAY = 'VNPAY';
    case MOMO = 'Momo';
    case ZALOPAY = 'ZaloPay';

    public function label(): string
    {
        return match ($this) {
            self::COD => 'Thanh toán khi nhận hàng',
            self::BANK => 'Thanh toán qua thẻ ngân hàng',
            self::VNPAY => 'Thanh toán VNPay QR',
            self::MOMO => 'Thanh toán ví MoMo',
            self::ZALOPAY => 'Thanh toán ZaloPay',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::COD => 'ri-money-dollar-circle-line',
            self::BANK => 'ri-bank-card-line',
            self::VNPAY => 'ri-qr-code-line',
            self::MOMO => 'ri-wallet-3-line',
            self::ZALOPAY => 'ri-wallet-line',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->label()
        ])->toArray();
    }

    public static function fromInput(string $input): self
    {
        return match (strtolower($input)) {
            'cod' => self::COD,
            'bank' => self::BANK,
            'vnpay' => self::VNPAY,
            'momo' => self::MOMO,
            'zalopay' => self::ZALOPAY,
            default => self::COD,
        };
    }
}
