<?php

namespace App\Enums;

enum PatientCategory: string
{
    case UMUM = 'umum';
    case BPJS = 'bpjs';
    case ASURANSI_SWASTA = 'asuransi_swasta';
    case PROGRAM_PEMERINTAH = 'program_pemerintah';
    case SISRUTE = 'sisrute';
    case TELEMEDIS = 'telemedis';

    public function label(): string
    {
        return match($this) {
            self::UMUM => 'Pasien Umum',
            self::BPJS => 'Pasien BPJS/Askes',
            self::ASURANSI_SWASTA => 'Pasien Asuransi Swasta (PPPK)',
            self::PROGRAM_PEMERINTAH => 'Pasien Program Pemerintah',
            self::SISRUTE => 'Pasien Rujukan IGD Regional Digital (SISRUTE)',
            self::TELEMEDIS => 'Pasien Telemedis / Video Konsultasi',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::UMUM => 'gray',
            self::BPJS => 'blue',
            self::ASURANSI_SWASTA => 'green',
            self::PROGRAM_PEMERINTAH => 'purple',
            self::SISRUTE => 'orange',
            self::TELEMEDIS => 'indigo',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::UMUM => 'user',
            self::BPJS => 'id-card',
            self::ASURANSI_SWASTA => 'shield-check',
            self::PROGRAM_PEMERINTAH => 'building-office',
            self::SISRUTE => 'arrow-path',
            self::TELEMEDIS => 'video-camera',
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

