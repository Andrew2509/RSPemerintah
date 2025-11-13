<?php

namespace App\Livewire;

use App\Enums\PatientCategory;
use App\Enums\ServiceType;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PatientEdit extends Component
{
    public Patient $patient;

    // Identitas Dasar
    public $nik = '';
    public $name = '';
    public $gender = 'L';
    public $birth_date = '';
    public $birth_place = '';
    public $address = '';
    public $phone = '';
    public $email = '';
    public $occupation = '';

    // Kategori & Layanan
    public $category = 'umum';
    public $service_type = '';
    public $status = 'active';

    // BPJS
    public $bpjs_number = '';
    public $bpjs_class = '';
    public $bpjs_active_until = '';

    // Asuransi Swasta
    public $insurance_company = '';
    public $insurance_policy_number = '';
    public $insurance_card_number = '';

    // Program Pemerintah
    public $government_program = '';
    public $government_program_number = '';

    // SISRUTE
    public $referral_number = '';
    public $referring_hospital = '';
    public $referral_reason = '';
    public $referral_date = '';

    // Telemedis
    public $is_telemedicine = false;
    public $telemedicine_platform = '';
    public $telemedicine_notes = '';

    // Keluarga & Penanggung Jawab
    public $family_name = '';
    public $family_relationship = '';
    public $family_phone = '';
    public $family_address = '';
    public $family_nik = '';

    // Kontak Gawat Darurat
    public $emergency_contact_name = '';
    public $emergency_contact_relationship = '';
    public $emergency_contact_phone = '';
    public $emergency_contact_phone2 = '';

    // Histori Alergi & Penyakit Kronis
    public $allergies = '';
    public $allergy_details = '';
    public $last_allergy_incident = '';
    public $medical_history = '';
    public $chronic_diseases = '';
    public $notes = '';

    protected $rules = [
        'nik' => 'nullable|digits:16|unique:patients,nik',
        'name' => 'required|string|max:255',
        'gender' => 'required|in:L,P',
        'birth_date' => 'required|date',
        'birth_place' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'occupation' => 'nullable|string|max:255',
        'category' => 'required|in:umum,bpjs,asuransi_swasta,program_pemerintah,sisrute,telemedis',
        'service_type' => 'nullable|in:urj,uri,ugd',
        'status' => 'required|in:active,inactive,deceased',
        'bpjs_number' => 'required_if:category,bpjs|nullable|string|max:50',
        'bpjs_class' => 'required_if:category,bpjs|nullable|in:1,2,3',
        'bpjs_active_until' => 'required_if:category,bpjs|nullable|date',
        'insurance_company' => 'required_if:category,asuransi_swasta|nullable|string|max:255',
        'insurance_policy_number' => 'required_if:category,asuransi_swasta|nullable|string|max:100',
        'insurance_card_number' => 'nullable|string|max:100',
        'government_program' => 'required_if:category,program_pemerintah|nullable|in:jampersal,jamkesda,other',
        'government_program_number' => 'required_if:category,program_pemerintah|nullable|string|max:100',
        'referral_number' => 'required_if:category,sisrute|nullable|string|max:100',
        'referring_hospital' => 'required_if:category,sisrute|nullable|string|max:255',
        'referral_reason' => 'required_if:category,sisrute|nullable|string',
        'referral_date' => 'required_if:category,sisrute|nullable|date',
        'is_telemedicine' => 'boolean',
        'telemedicine_platform' => 'required_if:category,telemedis|nullable|string|max:255',
        'telemedicine_notes' => 'nullable|string',
        'family_name' => 'nullable|string|max:255',
        'family_relationship' => 'nullable|string|max:100',
        'family_phone' => 'nullable|string|max:20',
        'family_address' => 'nullable|string',
        'family_nik' => 'nullable|digits:16',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_relationship' => 'nullable|string|max:100',
        'emergency_contact_phone' => 'nullable|string|max:20',
        'emergency_contact_phone2' => 'nullable|string|max:20',
        'allergies' => 'nullable|string',
        'allergy_details' => 'nullable|string',
        'last_allergy_incident' => 'nullable|date',
        'medical_history' => 'nullable|string',
        'chronic_diseases' => 'nullable|string',
        'notes' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Nama pasien wajib diisi.',
        'birth_date.required' => 'Tanggal lahir wajib diisi.',
        'bpjs_number.required_if' => 'Nomor BPJS wajib diisi untuk pasien BPJS.',
        'insurance_company.required_if' => 'Nama perusahaan asuransi wajib diisi.',
        'referral_number.required_if' => 'Nomor rujukan wajib diisi untuk pasien SISRUTE.',
        'telemedicine_platform.required_if' => 'Platform telemedis wajib diisi.',
    ];

    public function loadPatientData()
    {
        $p = $this->patient;

        // Identitas Dasar
        $this->nik = $p->nik ?? '';
        $this->name = $p->name ?? '';
        $this->gender = $p->gender ?? 'L';
        $this->birth_date = $p->birth_date ? \Carbon\Carbon::parse($p->birth_date)->format('Y-m-d') : '';
        $this->birth_place = $p->birth_place ?? '';
        $this->address = $p->address ?? '';
        $this->phone = $p->phone ?? '';
        $this->email = $p->email ?? '';
        $this->occupation = $p->occupation ?? '';

        // Kategori & Layanan
        $this->category = is_string($p->category) ? $p->category : $p->category->value;
        $this->service_type = $p->service_type ? (is_string($p->service_type) ? $p->service_type : $p->service_type->value) : '';
        $this->status = $p->status ?? 'active';

        // BPJS
        $this->bpjs_number = $p->bpjs_number ?? '';
        $this->bpjs_class = $p->bpjs_class ?? '';
        $this->bpjs_active_until = $p->bpjs_active_until ? \Carbon\Carbon::parse($p->bpjs_active_until)->format('Y-m-d') : '';

        // Asuransi Swasta
        $this->insurance_company = $p->insurance_company ?? '';
        $this->insurance_policy_number = $p->insurance_policy_number ?? '';
        $this->insurance_card_number = $p->insurance_card_number ?? '';

        // Program Pemerintah
        $this->government_program = $p->government_program ?? '';
        $this->government_program_number = $p->government_program_number ?? '';

        // SISRUTE
        $this->referral_number = $p->referral_number ?? '';
        $this->referring_hospital = $p->referring_hospital ?? '';
        $this->referral_reason = $p->referral_reason ?? '';
        $this->referral_date = $p->referral_date ? $p->referral_date->format('Y-m-d\TH:i') : '';

        // Telemedis
        $this->is_telemedicine = $p->is_telemedicine ?? false;
        $this->telemedicine_platform = $p->telemedicine_platform ?? '';
        $this->telemedicine_notes = $p->telemedicine_notes ?? '';

        // Keluarga & Penanggung Jawab
        $this->family_name = $p->family_name ?? '';
        $this->family_relationship = $p->family_relationship ?? '';
        $this->family_phone = $p->family_phone ?? '';
        $this->family_address = $p->family_address ?? '';
        $this->family_nik = $p->family_nik ?? '';

        // Kontak Gawat Darurat
        $this->emergency_contact_name = $p->emergency_contact_name ?? '';
        $this->emergency_contact_relationship = $p->emergency_contact_relationship ?? '';
        $this->emergency_contact_phone = $p->emergency_contact_phone ?? '';
        $this->emergency_contact_phone2 = $p->emergency_contact_phone2 ?? '';

        // Histori Alergi & Penyakit Kronis
        $this->allergies = $p->allergies ?? '';
        $this->allergy_details = $p->allergy_details ?? '';
        $this->last_allergy_incident = $p->last_allergy_incident ? \Carbon\Carbon::parse($p->last_allergy_incident)->format('Y-m-d') : '';
        $this->medical_history = $p->medical_history ?? '';
        $this->chronic_diseases = $p->chronic_diseases ?? '';
        $this->notes = $p->notes ?? '';
    }

    public $originalCategory = '';

    public function mount($id)
    {
        $this->patient = Patient::findOrFail($id);
        $this->loadPatientData();
        $this->originalCategory = $this->category;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'category') {
            // Hanya reset jika kategori benar-benar berubah dari yang asli
            if ($this->category !== $this->originalCategory) {
                $this->resetCategoryFields();
            }
        }
    }

    public function resetCategoryFields()
    {
        // Reset field yang tidak relevan dengan kategori baru
        if ($this->category !== 'bpjs') {
            $this->bpjs_number = '';
            $this->bpjs_class = '';
            $this->bpjs_active_until = '';
        }
        if ($this->category !== 'asuransi_swasta') {
            $this->insurance_company = '';
            $this->insurance_policy_number = '';
            $this->insurance_card_number = '';
        }
        if ($this->category !== 'program_pemerintah') {
            $this->government_program = '';
            $this->government_program_number = '';
        }
        if ($this->category !== 'sisrute') {
            $this->referral_number = '';
            $this->referring_hospital = '';
            $this->referral_reason = '';
            $this->referral_date = '';
        }
        if ($this->category !== 'telemedis') {
            $this->telemedicine_platform = '';
            $this->telemedicine_notes = '';
            $this->is_telemedicine = false;
        } else {
            $this->is_telemedicine = true;
        }
    }

    public function update()
    {
        // Update unique rule untuk NIK (ignore current patient)
        $this->rules['nik'] = 'nullable|digits:16|unique:patients,nik,' . $this->patient->id;

        $this->validate();

        $patientData = [
            'nik' => $this->nik ?: null,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place ?: null,
            'address' => $this->address ?: null,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'occupation' => $this->occupation ?: null,
            'family_name' => $this->family_name ?: null,
            'family_relationship' => $this->family_relationship ?: null,
            'family_phone' => $this->family_phone ?: null,
            'family_address' => $this->family_address ?: null,
            'family_nik' => $this->family_nik ?: null,
            'emergency_contact_name' => $this->emergency_contact_name ?: null,
            'emergency_contact_relationship' => $this->emergency_contact_relationship ?: null,
            'emergency_contact_phone' => $this->emergency_contact_phone ?: null,
            'emergency_contact_phone2' => $this->emergency_contact_phone2 ?: null,
            'category' => $this->category,
            'service_type' => $this->service_type ?: null,
            'status' => $this->status,
            'allergies' => $this->allergies ?: null,
            'allergy_details' => $this->allergy_details ?: null,
            'last_allergy_incident' => $this->last_allergy_incident ?: null,
            'medical_history' => $this->medical_history ?: null,
            'chronic_diseases' => $this->chronic_diseases ?: null,
            'notes' => $this->notes ?: null,
            'updated_by' => Auth::id() ?: null,
        ];

        // Tambahkan field sesuai kategori
        if ($this->category === 'bpjs') {
            $patientData['bpjs_number'] = $this->bpjs_number;
            $patientData['bpjs_class'] = $this->bpjs_class;
            $patientData['bpjs_active_until'] = $this->bpjs_active_until;
        } elseif ($this->category === 'asuransi_swasta') {
            $patientData['insurance_company'] = $this->insurance_company;
            $patientData['insurance_policy_number'] = $this->insurance_policy_number;
            $patientData['insurance_card_number'] = $this->insurance_card_number;
        } elseif ($this->category === 'program_pemerintah') {
            $patientData['government_program'] = $this->government_program;
            $patientData['government_program_number'] = $this->government_program_number;
        } elseif ($this->category === 'sisrute') {
            $patientData['referral_number'] = $this->referral_number;
            $patientData['referring_hospital'] = $this->referring_hospital;
            $patientData['referral_reason'] = $this->referral_reason;
            $patientData['referral_date'] = $this->referral_date;
        } elseif ($this->category === 'telemedis') {
            $patientData['is_telemedicine'] = true;
            $patientData['telemedicine_platform'] = $this->telemedicine_platform;
            $patientData['telemedicine_notes'] = $this->telemedicine_notes;
        }

        try {
            // Pastikan category dan service_type adalah string
            $patientData['category'] = (string) $this->category;
            if ($this->service_type) {
                $patientData['service_type'] = (string) $this->service_type;
            }

            // Pastikan semua field yang nullable benar-benar null jika kosong
            foreach ($patientData as $key => $value) {
                if ($value === '' || $value === []) {
                    $patientData[$key] = null;
                }
            }

            Log::info('Attempting to update patient', ['id' => $this->patient->id, 'data' => $patientData]);

            $this->patient->update($patientData);

            Log::info('Patient updated successfully', ['id' => $this->patient->id]);

            session()->flash('message', 'Data pasien berhasil diperbarui.');
            session()->flash('message_type', 'success');

            return $this->redirect(route('patients.show', $this->patient->id), navigate: true);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error updating patient', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            session()->flash('message', 'Error database: ' . $e->getMessage());
            session()->flash('message_type', 'error');
        } catch (\Exception $e) {
            Log::error('Error updating patient', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $patientData
            ]);

            session()->flash('message', 'Error memperbarui data: ' . $e->getMessage());
            session()->flash('message_type', 'error');
        }
    }

    public function render()
    {
        return view('livewire.patient-edit', [
            'categories' => PatientCategory::options(),
            'serviceTypes' => ServiceType::options(),
        ]);
    }
}
