<?php

namespace App\Enums;

class PackageType
{
    public const BOTTLE = 'bottle';
    public const STRIP = 'strip';
    public const AMPOULE = 'ampoule';
    public const TABLET = 'tablet';

    public static function values(): array
    {
        return [
            self::BOTTLE,
            self::STRIP,
            self::AMPOULE,
            self::TABLET,
        ];
    }
    public static function label(string $value): string
    {
        return match ($value) {
            self::BOTTLE => __('enums.package_type.bottle'),
            self::STRIP => __('enums.package_type.strip'),
            self::AMPOULE => __('enums.package_type.ampoule'),
            self::TABLET => __('enums.package_type.tablet'),
            default => __('enums.package_type.unknown'),
        };
    }
    public static function unitForPackage(string $packageType): string
    {
        $map = [
            self::BOTTLE => 'ml',
            self::STRIP => 'unit',
            self::AMPOULE => 'ml',
            self::TABLET => 'tablet',
        ];

        return $map[$packageType] ?? 'unknown';
    }
}
