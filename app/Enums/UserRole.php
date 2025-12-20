<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'Admin';
    case USER = 'User';
    case PRODUCT_MANAGER = 'Quản lý sản phẩm';
    case ORDER_MANAGER = 'Quản lý đơn hàng';

    public function label(): string
    {
        return $this->value;
    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::USER => 'secondary',
            self::PRODUCT_MANAGER => 'info',
            self::ORDER_MANAGER => 'warning',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => ['*'], // Full access
            self::PRODUCT_MANAGER => ['products.*', 'categories.*', 'suppliers.*'],
            self::ORDER_MANAGER => ['orders.*', 'customers.view'],
            self::USER => ['profile.*', 'orders.own'],
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

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function canAccessAdmin(): bool
    {
        return in_array($this, [self::ADMIN, self::PRODUCT_MANAGER, self::ORDER_MANAGER]);
    }
}
