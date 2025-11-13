<?php

namespace App\Livewire;

use App\Enums\TriageESILevel;
use App\Enums\TriagePriority;
use App\Models\Patient;
use App\Models\TriageRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TriageForm extends Component
{
    public $patientId;
    public $patient;

    // GCS
    public $gcs_eye = '';
    public $gcs_verbal = '';
    public $gcs_motor = '';
    public $gcs_total = '';

    // Vital Signs
    public $systolic_bp = '';
    public $diastolic_bp = '';
    public $heart_rate = '';
    public $respiratory_rate = '';
    public $oxygen_saturation = '';
    public $temperature = '';

    // ESI & Priority
    public $esi_level = '';
    public $priority = '';

    // Alergi Kritis
    public $has_critical_allergy = false;
    public $critical_allergy_details = '';

    // Consent Emergency Protocol
    public $consent_emergency_protocol = false;
    public $consent_reason = '';

    // Lainnya
    public $chief_complaint = '';
    public $triage_notes = '';

    protected $rules = [
        'gcs_eye' => 'nullable|integer|min:1|max:4',
        'gcs_verbal' => 'nullable|integer|min:1|max:5',
        'gcs_motor' => 'nullable|integer|min:1|max:6',
        'systolic_bp' => 'nullable|integer|min:50|max:250',
        'diastolic_bp' => 'nullable|integer|min:30|max:150',
        'heart_rate' => 'nullable|integer|min:30|max:200',
        'respiratory_rate' => 'nullable|integer|min:10|max:50',
        'oxygen_saturation' => 'nullable|numeric|min:0|max:100',
        'temperature' => 'nullable|numeric|min:30|max:45',
        'esi_level' => 'required|integer|min:1|max:5',
        'priority' => 'required|string',
        'has_critical_allergy' => 'boolean',
        'critical_allergy_details' => 'nullable|string',
        'consent_emergency_protocol' => 'boolean',
        'consent_reason' => 'nullable|string',
        'chief_complaint' => 'nullable|string',
        'triage_notes' => 'nullable|string',
    ];

    public function mount($id)
    {
        $this->patientId = $id;
        $this->patient = Patient::findOrFail($id);
        
        // Load alergi pasien jika ada
        if ($this->patient->allergies || $this->patient->allergy_details) {
            $this->has_critical_allergy = true;
            $this->critical_allergy_details = $this->patient->allergy_details ?? $this->patient->allergies;
        }
        
        // Cek apakah pasien tanpa keluarga
        if (!$this->patient->family_name && !$this->patient->emergency_contact_name) {
            $this->consent_reason = 'Pasien tanpa keluarga/penanggung jawab';
        }
    }

    public function updated($propertyName)
    {
        // Auto-calculate GCS Total
        if (in_array($propertyName, ['gcs_eye', 'gcs_verbal', 'gcs_motor'])) {
            $this->calculateGCSTotal();
        }
        
        // Auto-calculate ESI Level dan Priority
        if (in_array($propertyName, ['gcs_eye', 'gcs_verbal', 'gcs_motor', 'systolic_bp', 'diastolic_bp', 
            'heart_rate', 'oxygen_saturation', 'temperature', 'has_critical_allergy'])) {
            $this->calculateESIAndPriority();
        }
        
        // Auto-activate consent protocol jika kondisi terpenuhi
        if ($this->esi_level && $this->esi_level <= 2) {
            if (!$this->patient->family_name && !$this->patient->emergency_contact_name) {
                $this->consent_emergency_protocol = true;
                if (!$this->consent_reason) {
                    $this->consent_reason = 'Pasien dalam kondisi kritis (ESI ' . $this->esi_level . ') tanpa keluarga/penanggung jawab';
                }
            }
        }
    }

    public function calculateGCSTotal()
    {
        if ($this->gcs_eye && $this->gcs_verbal && $this->gcs_motor) {
            $this->gcs_total = (int)$this->gcs_eye + (int)$this->gcs_verbal + (int)$this->gcs_motor;
        } else {
            $this->gcs_total = '';
        }
    }

    public function calculateESIAndPriority()
    {
        // Buat temporary TriageRecord untuk kalkulasi
        $tempTriage = new TriageRecord();
        $tempTriage->gcs_eye = $this->gcs_eye ?: null;
        $tempTriage->gcs_verbal = $this->gcs_verbal ?: null;
        $tempTriage->gcs_motor = $this->gcs_motor ?: null;
        $tempTriage->gcs_total = $this->gcs_total ?: null;
        $tempTriage->systolic_bp = $this->systolic_bp ?: null;
        $tempTriage->diastolic_bp = $this->diastolic_bp ?: null;
        $tempTriage->heart_rate = $this->heart_rate ?: null;
        $tempTriage->oxygen_saturation = $this->oxygen_saturation ?: null;
        $tempTriage->temperature = $this->temperature ?: null;
        $tempTriage->has_critical_allergy = $this->has_critical_allergy;
        
        $this->esi_level = $tempTriage->determineESILevel();
        $this->priority = $tempTriage->determinePriority();
    }

    public function save()
    {
        $this->validate();

        // Calculate final values
        $this->calculateGCSTotal();
        $this->calculateESIAndPriority();

        $triageData = [
            'patient_id' => $this->patientId,
            'esi_level' => $this->esi_level,
            'priority' => $this->priority,
            'gcs_eye' => $this->gcs_eye ?: null,
            'gcs_verbal' => $this->gcs_verbal ?: null,
            'gcs_motor' => $this->gcs_motor ?: null,
            'gcs_total' => $this->gcs_total ?: null,
            'systolic_bp' => $this->systolic_bp ?: null,
            'diastolic_bp' => $this->diastolic_bp ?: null,
            'heart_rate' => $this->heart_rate ?: null,
            'respiratory_rate' => $this->respiratory_rate ?: null,
            'oxygen_saturation' => $this->oxygen_saturation ?: null,
            'temperature' => $this->temperature ?: null,
            'has_critical_allergy' => $this->has_critical_allergy,
            'critical_allergy_details' => $this->critical_allergy_details ?: null,
            'consent_emergency_protocol' => $this->consent_emergency_protocol,
            'consent_reason' => $this->consent_reason ?: null,
            'consent_issued_at' => $this->consent_emergency_protocol ? now() : null,
            'chief_complaint' => $this->chief_complaint ?: null,
            'triage_notes' => $this->triage_notes ?: null,
            'triage_by' => Auth::id(),
            'status' => 'active',
            'triage_time' => now(),
        ];

        TriageRecord::create($triageData);

        session()->flash('message', 'Triage berhasil disimpan. ESI Level: ' . $this->esi_level);
        session()->flash('message_type', 'success');

        return $this->redirect(route('patients.show', $this->patientId), navigate: true);
    }

    public function render()
    {
        return view('livewire.triage-form');
    }
}
