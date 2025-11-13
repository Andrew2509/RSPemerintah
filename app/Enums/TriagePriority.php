<?php

namespace App\Enums;

enum TriagePriority: string
{
    case RESUSCITATION = 'resuscitation'; // Merah - Immediate
    case EMERGENT = 'emergent'; // Orange - Very Urgent
    case URGENT = 'urgent'; // Kuning - Urgent
    case LESS_URGENT = 'less_urgent'; // Hijau - Less Urgent
    case NON_URGENT = 'non_urgent'; // Biru - Non-Urgent

    public function label(): string
    {
        return match($this) {
            self::RESUSCITATION => 'Resuscitation (Merah)',
            self::EMERGENT => 'Emergent (Orange)',
            self::URGENT => 'Urgent (Kuning)',
            self::LESS_URGENT => 'Less Urgent (Hijau)',
            self::NON_URGENT => 'Non-Urgent (Biru)',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::RESUSCITATION => '#dc2626', // Red
            self::EMERGENT => '#ea580c', // Orange
            self::URGENT => '#f59e0b', // Amber/Yellow
            self::LESS_URGENT => '#10b981', // Green
            self::NON_URGENT => '#3b82f6', // Blue
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
