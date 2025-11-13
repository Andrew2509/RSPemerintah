<?php

namespace App\Models;

use App\Enums\PatientCategory;
use App\Enums\ServiceType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medical_record_number',
        'national_mrn',
        'nik',
        'name',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'phone',
        'email',
        'occupation',
        'family_name',
        'family_relationship',
        'family_phone',
        'family_address',
        'family_nik',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'emergency_contact_phone2',
        'category',
        'bpjs_number',
        'bpjs_class',
        'bpjs_active_until',
        'insurance_company',
        'insurance_policy_number',
        'insurance_card_number',
        'government_program',
        'government_program_number',
        'referral_number',
        'referring_hospital',
        'referral_reason',
        'referral_date',
        'is_telemedicine',
        'telemedicine_platform',
        'telemedicine_notes',
        'telemedicine_referral_received_at',
        'telemedicine_expected_arrival',
        'telemedicine_actual_arrival',
        'telemedicine_arrival_status',
        'telemedicine_arrival_notes',
        'service_type',
        'status',
        'allergies',
        'allergy_details',
        'last_allergy_incident',
        'medical_history',
        'chronic_diseases',
        'notes',
        'qr_code',
        'wristband_number',
        'wristband_issued_at',
        'wristband_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'bpjs_active_until' => 'date',
        'referral_date' => 'datetime',
        'is_telemedicine' => 'boolean',
        'last_allergy_incident' => 'date',
        'wristband_issued_at' => 'datetime',
        'wristband_active' => 'boolean',
        'telemedicine_referral_received_at' => 'datetime',
        'telemedicine_expected_arrival' => 'datetime',
        'telemedicine_actual_arrival' => 'datetime',
        // Sementara comment enum casting untuk debugging
        // 'category' => PatientCategory::class,
        // 'service_type' => ServiceType::class,
    ];

    protected $attributes = [
        'category' => 'umum',
        'status' => 'active',
        'is_telemedicine' => false,
    ];

    /**
     * Relasi ke user yang membuat
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang terakhir update
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi ke triage records
     */
    public function triageRecords(): HasMany
    {
        return $this->hasMany(TriageRecord::class);
    }

    /**
     * Relasi ke triage record aktif terakhir
     */
    public function latestTriage()
    {
        return $this->hasOne(TriageRecord::class)->latestOfMany('triage_time');
    }

    /**
     * Accessor untuk usia
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return Carbon::parse($this->birth_date)->diffInYears(Carbon::now());
    }

    /**
     * Accessor untuk format nama lengkap dengan gelar
     */
    public function getFullNameAttribute(): string
    {
        if (!$this->name) {
            return '';
        }
        $prefix = $this->gender === 'L' ? 'Tn.' : 'Ny.';
        return "{$prefix} {$this->name}";
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByCategory($query, PatientCategory $category)
    {
        return $query->where('category', $category->value);
    }

    /**
     * Scope untuk filter berdasarkan jenis layanan
     */
    public function scopeByServiceType($query, ServiceType $serviceType)
    {
        return $query->where('service_type', $serviceType->value);
    }

    /**
     * Scope untuk pasien aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk pasien BPJS
     */
    public function scopeBpjs($query)
    {
        return $query->where('category', PatientCategory::BPJS->value);
    }

    /**
     * Scope untuk pasien telemedis
     */
    public function scopeTelemedicine($query)
    {
        return $query->where('is_telemedicine', true)
            ->orWhere('category', PatientCategory::TELEMEDIS->value);
    }

    /**
     * Scope untuk pasien rujukan SISRUTE
     */
    public function scopeReferral($query)
    {
        return $query->where('category', PatientCategory::SISRUTE->value)
            ->whereNotNull('referral_number');
    }

    /**
     * Generate nomor rekam medis otomatis
     */
    public static function generateMedicalRecordNumber(): string
    {
        $year = date('Y');
        $lastPatient = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPatient && $lastPatient->medical_record_number) {
            $lastSequence = substr($lastPatient->medical_record_number, -6);
            $sequence = (int) $lastSequence + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('RM%s%06d', $year, $sequence);
    }

    /**
     * Generate ID Rekam Medis Unik Nasional
     * Format: RS-XYZ-YYYYMMDD-XXXX
     */
    public static function generateNationalMRN(): string
    {
        $hospitalCode = 'XYZ'; // Kode RS, bisa diambil dari config
        $date = date('Ymd');
        $lastPatient = self::whereDate('created_at', today())
            ->whereNotNull('national_mrn')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPatient && $lastPatient->national_mrn) {
            $lastSequence = substr($lastPatient->national_mrn, -4);
            $sequence = (int) $lastSequence + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('RS-%s-%s-%04d', $hospitalCode, $date, $sequence);
    }

    /**
     * Generate QR Code untuk Patient Wristband
     */
    public function generateQRCode(): string
    {
        // Format: PATIENT-{MRN}-{TIMESTAMP}
        $timestamp = now()->format('YmdHis');
        return sprintf('PATIENT-%s-%s', $this->medical_record_number, $timestamp);
    }

    /**
     * Generate Wristband Number
     */
    public static function generateWristbandNumber(): string
    {
        $year = date('Y');
        $lastPatient = self::whereNotNull('wristband_number')
            ->whereYear('wristband_issued_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPatient && $lastPatient->wristband_number) {
            $lastSequence = substr($lastPatient->wristband_number, -6);
            $sequence = (int) $lastSequence + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('WB%s%06d', $year, $sequence);
    }

    /**
     * Hitung waktu kedatangan yang diharapkan untuk telemedis
     */
    public function calculateExpectedArrival(): ?\Carbon\Carbon
    {
        if (!$this->telemedicine_referral_received_at) {
            return null;
        }

        $receivedAt = Carbon::parse($this->telemedicine_referral_received_at);
        
        // UGD: 2 jam, URJ: 1 hari
        if ($this->service_type === 'ugd') {
            return $receivedAt->addHours(2);
        } elseif ($this->service_type === 'urj') {
            return $receivedAt->addDay();
        }

        return null;
    }

    /**
     * Cek status kedatangan telemedis
     */
    public function checkTelemedicineArrivalStatus(): ?string
    {
        if (!$this->telemedicine_expected_arrival) {
            return null;
        }

        if ($this->telemedicine_actual_arrival) {
            $expected = Carbon::parse($this->telemedicine_expected_arrival);
            $actual = Carbon::parse($this->telemedicine_actual_arrival);
            
            if ($actual->lte($expected)) {
                return 'on_time';
            } else {
                return 'late';
            }
        }

        $expected = Carbon::parse($this->telemedicine_expected_arrival);
        if (now()->gt($expected)) {
            return 'not_arrived';
        }

        return null;
    }

    /**
     * Boot method untuk auto-generate medical record number, national MRN, dan QR code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->medical_record_number)) {
                $patient->medical_record_number = self::generateMedicalRecordNumber();
            }
            
            if (empty($patient->national_mrn)) {
                $patient->national_mrn = self::generateNationalMRN();
            }
            
            if (empty($patient->qr_code)) {
                // QR code akan di-generate setelah patient dibuat
            }
        });

        static::created(function ($patient) {
            // Generate QR code setelah patient dibuat
            if (empty($patient->qr_code)) {
                $patient->qr_code = $patient->generateQRCode();
                $patient->saveQuietly();
            }
            
            // Generate wristband jika diperlukan
            if ($patient->service_type && empty($patient->wristband_number)) {
                $patient->wristband_number = self::generateWristbandNumber();
                $patient->wristband_issued_at = now();
                $patient->wristband_active = true;
                $patient->saveQuietly();
            }
            
            // Set expected arrival untuk telemedis
            if ($patient->category === 'telemedis' && $patient->service_type) {
                $patient->telemedicine_referral_received_at = now();
                $patient->telemedicine_expected_arrival = $patient->calculateExpectedArrival();
                $patient->saveQuietly();
            }
        });
    }
}
