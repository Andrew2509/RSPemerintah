<?php

namespace App\Models;

use App\Enums\TriageESILevel;
use App\Enums\TriagePriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TriageRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'esi_level',
        'priority',
        'gcs_eye',
        'gcs_verbal',
        'gcs_motor',
        'gcs_total',
        'systolic_bp',
        'diastolic_bp',
        'heart_rate',
        'respiratory_rate',
        'oxygen_saturation',
        'temperature',
        'vital_sign_score',
        'has_critical_allergy',
        'critical_allergy_details',
        'consent_emergency_protocol',
        'consent_reason',
        'consent_issued_at',
        'chief_complaint',
        'triage_notes',
        'triage_by',
        'status',
        'triage_time',
    ];

    protected $casts = [
        'esi_level' => 'integer',
        'gcs_eye' => 'integer',
        'gcs_verbal' => 'integer',
        'gcs_motor' => 'integer',
        'gcs_total' => 'integer',
        'systolic_bp' => 'integer',
        'diastolic_bp' => 'integer',
        'heart_rate' => 'integer',
        'respiratory_rate' => 'integer',
        'oxygen_saturation' => 'decimal:2',
        'temperature' => 'decimal:1',
        'vital_sign_score' => 'integer',
        'has_critical_allergy' => 'boolean',
        'consent_emergency_protocol' => 'boolean',
        'consent_issued_at' => 'datetime',
        'triage_time' => 'datetime',
    ];

    /**
     * Relasi ke pasien
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relasi ke petugas triage
     */
    public function triageOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triage_by');
    }

    /**
     * Hitung GCS Total
     */
    public function calculateGCSTotal(): ?int
    {
        if ($this->gcs_eye && $this->gcs_verbal && $this->gcs_motor) {
            return $this->gcs_eye + $this->gcs_verbal + $this->gcs_motor;
        }
        return null;
    }

    /**
     * Hitung Skor Vital Sign (0-100)
     * Skor berdasarkan normalitas vital sign
     */
    public function calculateVitalSignScore(): int
    {
        $score = 100;
        
        // Tekanan Darah (Normal: 90-140/60-90)
        if ($this->systolic_bp) {
            if ($this->systolic_bp < 90 || $this->systolic_bp > 180) {
                $score -= 30; // Kritis
            } elseif ($this->systolic_bp < 100 || $this->systolic_bp > 160) {
                $score -= 15; // Abnormal
            }
        }
        if ($this->diastolic_bp) {
            if ($this->diastolic_bp < 60 || $this->diastolic_bp > 110) {
                $score -= 20; // Kritis
            } elseif ($this->diastolic_bp < 70 || $this->diastolic_bp > 100) {
                $score -= 10; // Abnormal
            }
        }
        
        // Nadi (Normal: 60-100 bpm)
        if ($this->heart_rate) {
            if ($this->heart_rate < 50 || $this->heart_rate > 130) {
                $score -= 25; // Kritis
            } elseif ($this->heart_rate < 60 || $this->heart_rate > 110) {
                $score -= 10; // Abnormal
            }
        }
        
        // Saturasi O₂ (Normal: ≥95%)
        if ($this->oxygen_saturation) {
            if ($this->oxygen_saturation < 90) {
                $score -= 40; // Kritis
            } elseif ($this->oxygen_saturation < 95) {
                $score -= 20; // Abnormal
            }
        }
        
        // Suhu (Normal: 36.5-37.5°C)
        if ($this->temperature) {
            if ($this->temperature < 35 || $this->temperature > 39) {
                $score -= 20; // Kritis
            } elseif ($this->temperature < 36 || $this->temperature > 38) {
                $score -= 10; // Abnormal
            }
        }
        
        // GCS (Normal: 15)
        if ($this->gcs_total) {
            if ($this->gcs_total <= 8) {
                $score -= 50; // Kritis (Coma)
            } elseif ($this->gcs_total < 13) {
                $score -= 30; // Abnormal
            } elseif ($this->gcs_total < 15) {
                $score -= 10; // Sedikit abnormal
            }
        }
        
        return max(0, $score);
    }

    /**
     * Tentukan ESI Level berdasarkan skor
     */
    public function determineESILevel(): int
    {
        $vitalScore = $this->vital_sign_score ?? $this->calculateVitalSignScore();
        $gcs = $this->gcs_total ?? $this->calculateGCSTotal();
        
        // Level 1: Resuscitation - Kondisi mengancam nyawa
        if ($gcs && $gcs <= 8) {
            return 1; // Coma
        }
        if ($this->oxygen_saturation && $this->oxygen_saturation < 90) {
            return 1; // Hipoksia berat
        }
        if ($this->systolic_bp && $this->systolic_bp < 80) {
            return 1; // Syok
        }
        if ($vitalScore < 30) {
            return 1;
        }
        
        // Level 2: Emergent - Sangat darurat
        if ($vitalScore < 50) {
            return 2;
        }
        if ($gcs && $gcs < 13) {
            return 2;
        }
        if ($this->has_critical_allergy) {
            return 2;
        }
        
        // Level 3: Urgent
        if ($vitalScore < 70) {
            return 3;
        }
        if ($gcs && $gcs < 15) {
            return 3;
        }
        
        // Level 4: Less Urgent
        if ($vitalScore < 85) {
            return 4;
        }
        
        // Level 5: Non-Urgent
        return 5;
    }

    /**
     * Tentukan Priority berdasarkan ESI Level
     */
    public function determinePriority(): string
    {
        $esiLevel = $this->esi_level ?? $this->determineESILevel();
        
        return match($esiLevel) {
            1 => 'resuscitation',
            2 => 'emergent',
            3 => 'urgent',
            4 => 'less_urgent',
            5 => 'non_urgent',
            default => 'non_urgent',
        };
    }

    /**
     * Cek apakah perlu Consent Emergency Protocol
     */
    public function shouldActivateConsentProtocol(): bool
    {
        // Pasien kritis (ESI 1 atau 2) tanpa keluarga
        if ($this->esi_level <= 2) {
            $patient = $this->patient;
            if (!$patient->family_name && !$patient->emergency_contact_name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Boot method untuk auto-calculate
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($triage) {
            // Calculate GCS Total
            if ($triage->gcs_eye && $triage->gcs_verbal && $triage->gcs_motor) {
                $triage->gcs_total = $triage->calculateGCSTotal();
            }
            
            // Calculate Vital Sign Score
            $triage->vital_sign_score = $triage->calculateVitalSignScore();
            
            // Determine ESI Level
            if (!$triage->esi_level) {
                $triage->esi_level = $triage->determineESILevel();
            }
            
            // Determine Priority
            if (!$triage->priority) {
                $triage->priority = $triage->determinePriority();
            }
            
            // Auto-activate Consent Emergency Protocol jika diperlukan
            if ($triage->shouldActivateConsentProtocol() && !$triage->consent_emergency_protocol) {
                $triage->consent_emergency_protocol = true;
                $triage->consent_reason = 'Pasien dalam kondisi kritis (ESI ' . $triage->esi_level . ') tanpa keluarga/penanggung jawab';
                $triage->consent_issued_at = now();
            }
        });
    }
}
