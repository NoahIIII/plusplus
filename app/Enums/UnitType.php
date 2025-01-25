<?php

namespace App\Enums;

class UnitType
{
    public const TABLET = 'tablet';
    public const CAPSULE = 'capsule';
    public const ML = 'ml';
    public const GRAM = 'g';
    public const UNIT = 'unit';

    public static function values(): array
    {
        return [
            self::TABLET,
            self::CAPSULE,
            self::ML,
            self::GRAM,
            self::UNIT
        ];
    }

    public static function label(string $value): string
    {
        return match ($value) {
            self::TABLET => __('enums.unit_type.tablet'),
            self::CAPSULE => __('enums.unit_type.capsule'),
            self::ML => __('enums.unit_type.ml'),
            self::GRAM => __('enums.unit_type.g'),
            self::UNIT => __('enums.unit_type.unit'),
            default => __('enums.unit_type.unknown'),
        };
    }
}
