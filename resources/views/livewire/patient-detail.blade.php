<div class="patient-detail-container">
    <div class="patient-detail-wrapper">
        <!-- Header -->
        <div class="patient-detail-header">
            <div class="patient-detail-header-info">
                <h2>Detail Pasien</h2>
                <p>Informasi lengkap pasien</p>
            </div>
            <div class="patient-detail-header-actions">
                <a href="{{ route('patients.index') }}" class="btn-back">
                    Kembali
                </a>
                @if($patient->service_type === 'ugd')
                <a href="{{ route('patients.triage', $patient->id) }}" class="btn-edit" style="background: #dc2626;">
                    Triage
                </a>
                @endif
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn-edit">
                    Edit Pasien
                </a>
            </div>
        </div>

        <div class="patient-detail-card">
            <!-- Identitas Dasar -->
            <div class="patient-detail-section">
                <h3 class="patient-detail-section-title">Identitas Dasar</h3>
                <div class="patient-detail-grid">
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nomor Rekam Medis</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->medical_record_number }}</p>
                    </div>
                    @if($patient->national_mrn)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">ID Rekam Medis Unik Nasional</label>
                        <p class="patient-detail-value patient-detail-value-bold" style="color: #2563eb;">{{ $patient->national_mrn }}</p>
                    </div>
                    @endif
                    @if($patient->qr_code)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">QR Code Wristband</label>
                        <p class="patient-detail-value patient-detail-value-bold" style="font-family: monospace; font-size: 12px;">{{ $patient->qr_code }}</p>
                    </div>
                    @endif
                    @if($patient->wristband_number)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nomor Wristband</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->wristband_number }}</p>
                    </div>
                    @endif
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nama Lengkap</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->full_name }}</p>
                    </div>
                    @if($patient->nik)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">NIK</label>
                        <p class="patient-detail-value">{{ $patient->nik }}</p>
                    </div>
                    @endif
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Jenis Kelamin</label>
                        <p class="patient-detail-value">{{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Tanggal Lahir</label>
                        <p class="patient-detail-value">
                            @if($patient->birth_date)
                                {{ $patient->birth_date->format('d/m/Y') }} 
                                @if($patient->age)
                                    ({{ $patient->age }} tahun)
                                @endif
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    @if($patient->birth_place)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Tempat Lahir</label>
                        <p class="patient-detail-value">{{ $patient->birth_place }}</p>
                    </div>
                    @endif
                    @if($patient->phone)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">No. Telepon</label>
                        <p class="patient-detail-value">{{ $patient->phone }}</p>
                    </div>
                    @endif
                    @if($patient->email)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Email</label>
                        <p class="patient-detail-value">{{ $patient->email }}</p>
                    </div>
                    @endif
                    @if($patient->occupation)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Pekerjaan</label>
                        <p class="patient-detail-value">{{ $patient->occupation }}</p>
                    </div>
                    @endif
                    @if($patient->address)
                    <div class="patient-detail-info-item patient-detail-grid-full">
                        <label class="patient-detail-label">Alamat</label>
                        <p class="patient-detail-value">{{ $patient->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Kategori & Layanan -->
            <div class="patient-detail-section">
                <h3 class="patient-detail-section-title">Kategori Pasien & Layanan</h3>
                <div class="patient-detail-grid">
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Kategori</label>
                        @php
                            $categoryValue = is_string($patient->category) ? $patient->category : $patient->category->value;
                            $categoryEnum = \App\Enums\PatientCategory::tryFrom($categoryValue);
                            $categoryLabel = $categoryEnum ? $categoryEnum->label() : ucfirst(str_replace('_', ' ', $categoryValue));
                        @endphp
                        <p class="patient-detail-value patient-detail-value-bold">{{ $categoryLabel }}</p>
                    </div>
                    @if($patient->service_type)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Jenis Layanan</label>
                        @php
                            $serviceValue = is_string($patient->service_type) ? $patient->service_type : $patient->service_type->value;
                            $serviceEnum = \App\Enums\ServiceType::tryFrom($serviceValue);
                            $serviceLabel = $serviceEnum ? $serviceEnum->label() : ucfirst(str_replace('_', ' ', $serviceValue));
                        @endphp
                        <p class="patient-detail-value">{{ $serviceLabel }}</p>
                    </div>
                    @endif
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Status</label>
                        <p class="patient-detail-value">
                            <span class="patient-detail-status-badge 
                                @if($patient->status === 'active') active
                                @elseif($patient->status === 'inactive') inactive
                                @else deceased
                                @endif">
                                {{ ucfirst($patient->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informasi Kategori Spesifik -->
            @if($patient->category === 'bpjs' && $patient->bpjs_number)
            <div class="patient-detail-category-section bpjs">
                <h3 class="patient-detail-section-title">Informasi BPJS</h3>
                <div class="patient-detail-category-grid">
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nomor BPJS</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->bpjs_number }}</p>
                    </div>
                    @if($patient->bpjs_class)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Kelas BPJS</label>
                        <p class="patient-detail-value">Kelas {{ $patient->bpjs_class }}</p>
                    </div>
                    @endif
                    @if($patient->bpjs_active_until)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Berlaku Sampai</label>
                        <p class="patient-detail-value">{{ $patient->bpjs_active_until->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($patient->category === 'asuransi_swasta' && $patient->insurance_company)
            <div class="patient-detail-category-section asuransi">
                <h3 class="patient-detail-section-title">Informasi Asuransi Swasta</h3>
                <div class="patient-detail-category-grid two-col">
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Perusahaan Asuransi</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->insurance_company }}</p>
                    </div>
                    @if($patient->insurance_policy_number)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nomor Polis</label>
                        <p class="patient-detail-value">{{ $patient->insurance_policy_number }}</p>
                    </div>
                    @endif
                    @if($patient->insurance_card_number)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nomor Kartu</label>
                        <p class="patient-detail-value">{{ $patient->insurance_card_number }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($patient->category === 'sisrute' && $patient->referral_number)
            <div class="patient-detail-category-section sisrute">
                <h3 class="patient-detail-section-title">Informasi Rujukan SISRUTE</h3>
                <div class="patient-detail-category-grid two-col">
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nomor Rujukan</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->referral_number }}</p>
                    </div>
                    @if($patient->referring_hospital)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Rumah Sakit Pengirim</label>
                        <p class="patient-detail-value">{{ $patient->referring_hospital }}</p>
                    </div>
                    @endif
                    @if($patient->referral_date)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Tanggal Rujukan</label>
                        <p class="patient-detail-value">{{ $patient->referral_date->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($patient->referral_reason)
                    <div class="patient-detail-info-item patient-detail-category-grid-full">
                        <label class="patient-detail-label">Alasan Rujukan</label>
                        <p class="patient-detail-value">{{ $patient->referral_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Informasi Medis -->
            <!-- Profil Keluarga & Penanggung Jawab -->
            @if($patient->family_name || $patient->family_phone)
            <div class="patient-detail-section">
                <h3 class="patient-detail-section-title">Profil Keluarga & Penanggung Jawab</h3>
                <div class="patient-detail-grid">
                    @if($patient->family_name)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nama</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->family_name }}</p>
                    </div>
                    @endif
                    @if($patient->family_relationship)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Hubungan</label>
                        <p class="patient-detail-value">{{ $patient->family_relationship }}</p>
                    </div>
                    @endif
                    @if($patient->family_nik)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">NIK</label>
                        <p class="patient-detail-value">{{ $patient->family_nik }}</p>
                    </div>
                    @endif
                    @if($patient->family_phone)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">No. Telepon</label>
                        <p class="patient-detail-value">{{ $patient->family_phone }}</p>
                    </div>
                    @endif
                    @if($patient->family_address)
                    <div class="patient-detail-info-item patient-detail-grid-full">
                        <label class="patient-detail-label">Alamat</label>
                        <p class="patient-detail-value">{{ $patient->family_address }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Kontak Gawat Darurat -->
            @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
            <div class="patient-detail-section">
                <h3 class="patient-detail-section-title">Kontak Gawat Darurat</h3>
                <div class="patient-detail-grid">
                    @if($patient->emergency_contact_name)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Nama</label>
                        <p class="patient-detail-value patient-detail-value-bold">{{ $patient->emergency_contact_name }}</p>
                    </div>
                    @endif
                    @if($patient->emergency_contact_relationship)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Hubungan</label>
                        <p class="patient-detail-value">{{ $patient->emergency_contact_relationship }}</p>
                    </div>
                    @endif
                    @if($patient->emergency_contact_phone)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">No. Telepon Utama</label>
                        <p class="patient-detail-value" style="color: #dc2626; font-weight: 600;">{{ $patient->emergency_contact_phone }}</p>
                    </div>
                    @endif
                    @if($patient->emergency_contact_phone2)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">No. Telepon Alternatif</label>
                        <p class="patient-detail-value">{{ $patient->emergency_contact_phone2 }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Histori Alergi & Riwayat Penyakit Kronis -->
            @if($patient->allergies || $patient->allergy_details || $patient->chronic_diseases || $patient->medical_history || $patient->notes)
            <div class="patient-detail-section">
                <h3 class="patient-detail-section-title">Histori Alergi & Riwayat Penyakit Kronis</h3>
                <div class="patient-detail-medical-list">
                    @if($patient->allergies)
                    <div class="patient-detail-medical-item">
                        <label class="patient-detail-label">Alergi</label>
                        <p class="patient-detail-value">{{ $patient->allergies }}</p>
                    </div>
                    @endif
                    @if($patient->allergy_details)
                    <div class="patient-detail-medical-item">
                        <label class="patient-detail-label">Detail Alergi</label>
                        <p class="patient-detail-value">{{ $patient->allergy_details }}</p>
                    </div>
                    @endif
                    @if($patient->last_allergy_incident)
                    <div class="patient-detail-medical-item">
                        <label class="patient-detail-label">Tanggal Insiden Alergi Terakhir</label>
                        <p class="patient-detail-value">{{ $patient->last_allergy_incident->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($patient->chronic_diseases)
                    <div class="patient-detail-medical-item">
                        <label class="patient-detail-label">Riwayat Penyakit Kronis</label>
                        <p class="patient-detail-value" style="color: #dc2626; font-weight: 500;">{{ $patient->chronic_diseases }}</p>
                    </div>
                    @endif
                    @if($patient->medical_history)
                    <div class="patient-detail-medical-item">
                        <label class="patient-detail-label">Riwayat Medis Lainnya</label>
                        <p class="patient-detail-value">{{ $patient->medical_history }}</p>
                    </div>
                    @endif
                    @if($patient->notes)
                    <div class="patient-detail-medical-item">
                        <label class="patient-detail-label">Catatan Tambahan</label>
                        <p class="patient-detail-value">{{ $patient->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Tracking Telemedis -->
            @if($patient->category === 'telemedis' && $patient->telemedicine_referral_received_at)
            <div class="patient-detail-section">
                <h3 class="patient-detail-section-title">Tracking Rujukan Digital Telemedis</h3>
                <div class="patient-detail-grid">
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Waktu Menerima Rujukan</label>
                        <p class="patient-detail-value">{{ $patient->telemedicine_referral_received_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($patient->telemedicine_expected_arrival)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Waktu Kedatangan Diharapkan</label>
                        <p class="patient-detail-value patient-detail-value-bold">
                            {{ $patient->telemedicine_expected_arrival->format('d/m/Y H:i') }}
                            @php
                                $expected = \Carbon\Carbon::parse($patient->telemedicine_expected_arrival);
                                $now = now();
                                if ($patient->service_type === 'ugd') {
                                    $deadline = '2 jam';
                                } else {
                                    $deadline = '1 hari';
                                }
                            @endphp
                            <span style="font-size: 12px; color: #6b7280;">({{ $deadline }})</span>
                        </p>
                    </div>
                    @endif
                    @if($patient->telemedicine_actual_arrival)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Waktu Kedatangan Aktual</label>
                        <p class="patient-detail-value">{{ $patient->telemedicine_actual_arrival->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($patient->telemedicine_arrival_status)
                    <div class="patient-detail-info-item">
                        <label class="patient-detail-label">Status Kedatangan</label>
                        <p class="patient-detail-value">
                            @if($patient->telemedicine_arrival_status === 'on_time')
                                <span style="color: #16a34a; font-weight: 600;">✓ Tepat Waktu</span>
                            @elseif($patient->telemedicine_arrival_status === 'late')
                                <span style="color: #dc2626; font-weight: 600;">⚠ Terlambat</span>
                            @else
                                <span style="color: #dc2626; font-weight: 600;">✗ Belum Datang</span>
                            @endif
                        </p>
                    </div>
                    @endif
                    @if($patient->telemedicine_arrival_notes)
                    <div class="patient-detail-info-item patient-detail-grid-full">
                        <label class="patient-detail-label">Catatan Kedatangan</label>
                        <p class="patient-detail-value">{{ $patient->telemedicine_arrival_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Triage Records -->
            @if($patient->triageRecords && $patient->triageRecords->count() > 0)
            <div class="patient-detail-section">
                <h3 class="patient-detail-section-title">Riwayat Triage</h3>
                @foreach($patient->triageRecords->sortByDesc('triage_time') as $triage)
                <div class="patient-detail-triage-card" style="margin-bottom: 16px; padding: 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid {{ \App\Enums\TriageESILevel::from($triage->esi_level)->color() }};">
                    <div class="patient-detail-grid">
                        <div class="patient-detail-info-item">
                            <label class="patient-detail-label">ESI Level</label>
                            <p class="patient-detail-value patient-detail-value-bold" style="color: {{ \App\Enums\TriageESILevel::from($triage->esi_level)->color() }};">
                                Level {{ $triage->esi_level }} - {{ \App\Enums\TriageESILevel::from($triage->esi_level)->label() }}
                            </p>
                        </div>
                        <div class="patient-detail-info-item">
                            <label class="patient-detail-label">Prioritas</label>
                            <p class="patient-detail-value" style="color: {{ \App\Enums\TriagePriority::from($triage->priority)->color() }}; font-weight: 600;">
                                {{ \App\Enums\TriagePriority::from($triage->priority)->label() }}
                            </p>
                        </div>
                        @if($triage->gcs_total)
                        <div class="patient-detail-info-item">
                            <label class="patient-detail-label">GCS Total</label>
                            <p class="patient-detail-value patient-detail-value-bold">{{ $triage->gcs_total }}/15</p>
                        </div>
                        @endif
                        @if($triage->systolic_bp && $triage->diastolic_bp)
                        <div class="patient-detail-info-item">
                            <label class="patient-detail-label">Tekanan Darah</label>
                            <p class="patient-detail-value">{{ $triage->systolic_bp }}/{{ $triage->diastolic_bp }} mmHg</p>
                        </div>
                        @endif
                        @if($triage->oxygen_saturation)
                        <div class="patient-detail-info-item">
                            <label class="patient-detail-label">Saturasi O₂</label>
                            <p class="patient-detail-value">{{ $triage->oxygen_saturation }}%</p>
                        </div>
                        @endif
                        @if($triage->consent_emergency_protocol)
                        <div class="patient-detail-info-item patient-detail-grid-full">
                            <label class="patient-detail-label">Consent Emergency Protocol</label>
                            <p class="patient-detail-value" style="color: #dc2626; font-weight: 600;">
                                ⚠ Aktif - {{ $triage->consent_reason }}
                            </p>
                        </div>
                        @endif
                        <div class="patient-detail-info-item patient-detail-grid-full">
                            <label class="patient-detail-label">Waktu Triage</label>
                            <p class="patient-detail-value">{{ $triage->triage_time->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Metadata -->
            <div class="patient-detail-metadata">
                <div class="patient-detail-metadata-grid">
                    <div class="patient-detail-metadata-item">
                        <label class="patient-detail-metadata-label">Tanggal Daftar</label>
                        <p class="patient-detail-metadata-value">{{ $patient->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($patient->updated_at)
                    <div class="patient-detail-metadata-item">
                        <label class="patient-detail-metadata-label">Terakhir Diupdate</label>
                        <p class="patient-detail-metadata-value">{{ $patient->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
