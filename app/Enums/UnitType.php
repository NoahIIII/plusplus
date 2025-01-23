<?php

namespace App\Enums;

class UnitType
{
    public const TABLET = 'tablet';
    public const CAPSULE = 'capsule';
    public const ML = 'ml';
    public const GRAM = 'g';

    public static function values(): array
    {
        return [
            self::TABLET,
            self::CAPSULE,
            self::ML,
            self::GRAM,
        ];
    }

    public static function label(string $value): string
    {
        return match ($value) {
            self::TABLET => __('enums.unit_type.tablet'),
            self::CAPSULE => __('enums.unit_type.capsule'),
            self::ML => __('enums.unit_type.ml'),
            self::GRAM => __('enums.unit_type.gram'),
            default => __('enums.unit_type.unknown'),
        };
    }
}
