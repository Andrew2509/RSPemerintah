<?php

namespace App\Enums;

enum TriageESILevel: int
{
    case LEVEL_1 = 1; // Resuscitation - Immediate
    case LEVEL_2 = 2; // Emergent - Very Urgent
    case LEVEL_3 = 3; // Urgent
    case LEVEL_4 = 4; // Less Urgent
    case LEVEL_5 = 5; // Non-Urgent

    public function label(): string
    {
        return match($this) {
            self::LEVEL_1 => 'Level 1 - Resuscitation (Immediate)',
            self::LEVEL_2 => 'Level 2 - Emergent (Very Urgent)',
            self::LEVEL_3 => 'Level 3 - Urgent',
            self::LEVEL_4 => 'Level 4 - Less Urgent',
            self::LEVEL_5 => 'Level 5 - Non-Urgent',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LEVEL_1 => '#dc2626', // Red
            self::LEVEL_2 => '#ea580c', // Orange
            self::LEVEL_3 => '#f59e0b', // Amber
            self::LEVEL_4 => '#3b82f6', // Blue
            self::LEVEL_5 => '#10b981', // Green
        };
    }

    public function description(): string
    {
        return match($this) {
            self::LEVEL_1 => 'Pasien memerlukan resusitasi segera, kondisi mengancam nyawa',
            self::LEVEL_2 => 'Pasien dalam kondisi darurat, memerlukan penanganan segera',
            self::LEVEL_3 => 'Pasien memerlukan penanganan dalam waktu singkat',
            self::LEVEL_4 => 'Pasien memerlukan penanganan namun tidak darurat',
            self::LEVEL_5 => 'Pasien dapat ditangani dengan penjadwalan biasa',
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
