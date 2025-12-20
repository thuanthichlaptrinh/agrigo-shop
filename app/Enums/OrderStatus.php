<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'Chờ xác nhận';
    case CONFIRMED = 'Đã xác nhận';
    case SHIPPING = 'Đang giao';
    case COMPLETED = 'Đã giao';
    case CANCELLED = 'Đã hủy';

    public function label(): string
    {
        return $this->value;
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'info',
            self::SHIPPING => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'ri-time-line',
            self::CONFIRMED => 'ri-checkbox-circle-line',
            self::SHIPPING => 'ri-truck-line',
            self::COMPLETED => 'ri-check-double-line',
            self::CANCELLED => 'ri-close-circle-line',
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

    public function canTransitionTo(self $newStatus): bool
    {
        $transitions = [
            self::PENDING->value => [self::CONFIRMED, self::SHIPPING, self::CANCELLED],
            self::CONFIRMED->value => [self::SHIPPING, self::CANCELLED],
            self::SHIPPING->value => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED->value => [],
            self::CANCELLED->value => [],
        ];

        return in_array($newStatus, $transitions[$this->value] ?? []);
    }
}
