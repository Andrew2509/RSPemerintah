<?php

namespace App\Enums;

enum ServiceType: string
{
    case URJ = 'urj'; // Unit Rawat Jalan
    case URI = 'uri'; // Unit Rawat Inap
    case UGD = 'ugd'; // Unit Gawat Darurat

    public function label(): string
    {
        return match($this) {
            self::URJ => 'Rawat Jalan',
            self::URI => 'Rawat Inap',
            self::UGD => 'Unit Gawat Darurat',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::URJ => 'blue',
            self::URI => 'green',
            self::UGD => 'red',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}

