<div class="triage-form-container">
    <div class="triage-form-wrapper">
        <!-- Header -->
        <div class="triage-form-header">
            <div class="triage-form-header-info">
                <h2>Triage Pasien</h2>
                <p>Pasien: <span>{{ $patient->name }}</span> ({{ $patient->medical_record_number }})</p>
            </div>
            <div class="triage-form-header-actions">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn-back">
                    Kembali
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert-message {{ session('message_type') === 'error' ? 'error' : 'success' }}">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="triage-form">
            <!-- GCS (Glasgow Coma Scale) -->
            <div class="triage-form-section">
                <h3 class="triage-form-section-title">Skor GCS (Glasgow Coma Scale)</h3>
                <div class="triage-form-grid">
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Eye Response (1-4)</label>
                        <select wire:model.live="gcs_eye" class="triage-form-select">
                            <option value="">Pilih</option>
                            <option value="4">4 - Spontan membuka mata</option>
                            <option value="3">3 - Membuka mata saat dipanggil</option>
                            <option value="2">2 - Membuka mata saat nyeri</option>
                            <option value="1">1 - Tidak membuka mata</option>
                        </select>
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Verbal Response (1-5)</label>
                        <select wire:model.live="gcs_verbal" class="triage-form-select">
                            <option value="">Pilih</option>
                            <option value="5">5 - Orientasi baik</option>
                            <option value="4">4 - Bingung</option>
                            <option value="3">3 - Kata-kata tidak tepat</option>
                            <option value="2">2 - Suara tidak jelas</option>
                            <option value="1">1 - Tidak ada suara</option>
                        </select>
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Motor Response (1-6)</label>
                        <select wire:model.live="gcs_motor" class="triage-form-select">
                            <option value="">Pilih</option>
                            <option value="6">6 - Mengikuti perintah</option>
                            <option value="5">5 - Lokalisasi nyeri</option>
                            <option value="4">4 - Menarik dari nyeri</option>
                            <option value="3">3 - Fleksi abnormal</option>
                            <option value="2">2 - Ekstensi abnormal</option>
                            <option value="1">1 - Tidak ada respon</option>
                        </select>
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">GCS Total</label>
                        <input type="text" value="{{ $gcs_total }}" disabled
                            class="triage-form-input" style="background: #f3f4f6; font-weight: 600;">
                        <p class="triage-form-help-text">Otomatis dihitung (3-15)</p>
                    </div>
                </div>
            </div>

            <!-- Vital Signs (IoT Sensor) -->
            <div class="triage-form-section">
                <h3 class="triage-form-section-title">Vital Signs (Sensor IoT)</h3>
                <div class="triage-form-grid">
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Tekanan Darah Sistolik (mmHg)</label>
                        <input type="number" wire:model.live="systolic_bp" min="50" max="250"
                            class="triage-form-input"
                            placeholder="90-140">
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Tekanan Darah Diastolik (mmHg)</label>
                        <input type="number" wire:model.live="diastolic_bp" min="30" max="150"
                            class="triage-form-input"
                            placeholder="60-90">
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Nadi (bpm)</label>
                        <input type="number" wire:model.live="heart_rate" min="30" max="200"
                            class="triage-form-input"
                            placeholder="60-100">
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Laju Pernapasan (rpm)</label>
                        <input type="number" wire:model.live="respiratory_rate" min="10" max="50"
                            class="triage-form-input"
                            placeholder="12-20">
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Saturasi O₂ (%)</label>
                        <input type="number" wire:model.live="oxygen_saturation" step="0.1" min="0" max="100"
                            class="triage-form-input"
                            placeholder="≥95">
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Suhu Tubuh (°C)</label>
                        <input type="number" wire:model.live="temperature" step="0.1" min="30" max="45"
                            class="triage-form-input"
                            placeholder="36.5-37.5">
                    </div>
                </div>
            </div>

            <!-- ESI Level & Priority -->
            <div class="triage-form-section">
                <h3 class="triage-form-section-title">ESI Level & Prioritas</h3>
                <div class="triage-form-grid">
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">ESI Level <span class="required">*</span></label>
                        <select wire:model.live="esi_level" class="triage-form-select" style="font-weight: 600;">
                            <option value="">Pilih ESI Level</option>
                            <option value="1">Level 1 - Resuscitation (Merah)</option>
                            <option value="2">Level 2 - Emergent (Orange)</option>
                            <option value="3">Level 3 - Urgent (Kuning)</option>
                            <option value="4">Level 4 - Less Urgent (Hijau)</option>
                            <option value="5">Level 5 - Non-Urgent (Biru)</option>
                        </select>
                        @if($esi_level)
                            @php
                                $esiEnum = \App\Enums\TriageESILevel::from((int)$esi_level);
                            @endphp
                            <p class="triage-form-help-text" style="color: {{ $esiEnum->color() }}; font-weight: 600;">
                                {{ $esiEnum->description() }}
                            </p>
                        @endif
                        @error('esi_level') <span class="triage-form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">Prioritas <span class="required">*</span></label>
                        <select wire:model="priority" class="triage-form-select">
                            <option value="">Pilih Prioritas</option>
                            <option value="resuscitation">Resuscitation (Merah)</option>
                            <option value="emergent">Emergent (Orange)</option>
                            <option value="urgent">Urgent (Kuning)</option>
                            <option value="less_urgent">Less Urgent (Hijau)</option>
                            <option value="non_urgent">Non-Urgent (Biru)</option>
                        </select>
                        @error('priority') <span class="triage-form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Alergi Kritis -->
            <div class="triage-form-section">
                <h3 class="triage-form-section-title">Riwayat Alergi Kritis</h3>
                <div class="triage-form-grid">
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">
                            <input type="checkbox" wire:model.live="has_critical_allergy" class="triage-form-checkbox">
                            Pasien memiliki alergi kritis
                        </label>
                        @if($patient->allergies || $patient->allergy_details)
                            <p class="triage-form-help-text" style="color: #dc2626;">
                                ⚠ Alergi pasien: {{ $patient->allergy_details ?? $patient->allergies }}
                            </p>
                        @endif
                    </div>
                    @if($has_critical_allergy)
                    <div class="triage-form-form-group triage-form-grid-full">
                        <label class="triage-form-label">Detail Alergi Kritis</label>
                        <textarea wire:model="critical_allergy_details" rows="3"
                            class="triage-form-textarea"
                            placeholder="Detail alergi kritis pasien"></textarea>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Consent Emergency Protocol -->
            @if(!$patient->family_name && !$patient->emergency_contact_name)
            <div class="triage-form-section" style="border: 2px solid #dc2626; background: #fef2f2;">
                <h3 class="triage-form-section-title" style="color: #dc2626;">⚠ Consent Emergency Protocol</h3>
                <div class="triage-form-grid">
                    <div class="triage-form-form-group">
                        <label class="triage-form-label">
                            <input type="checkbox" wire:model.live="consent_emergency_protocol" class="triage-form-checkbox">
                            Aktifkan Consent Emergency Protocol
                        </label>
                        <p class="triage-form-help-text" style="color: #dc2626;">
                            Pasien tidak memiliki keluarga/penanggung jawab. Jika pasien dalam kondisi kritis (ESI 1-2), 
                            sistem akan otomatis mengaktifkan izin tindakan darurat.
                        </p>
                    </div>
                    @if($consent_emergency_protocol)
                    <div class="triage-form-form-group triage-form-grid-full">
                        <label class="triage-form-label">Alasan Consent</label>
                        <textarea wire:model="consent_reason" rows="2"
                            class="triage-form-textarea"></textarea>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Keluhan & Catatan -->
            <div class="triage-form-section">
                <h3 class="triage-form-section-title">Keluhan & Catatan</h3>
                <div class="triage-form-grid">
                    <div class="triage-form-form-group triage-form-grid-full">
                        <label class="triage-form-label">Keluhan Utama</label>
                        <textarea wire:model="chief_complaint" rows="3"
                            class="triage-form-textarea"
                            placeholder="Keluhan utama pasien"></textarea>
                    </div>
                    <div class="triage-form-form-group triage-form-grid-full">
                        <label class="triage-form-label">Catatan Triage</label>
                        <textarea wire:model="triage_notes" rows="3"
                            class="triage-form-textarea"
                            placeholder="Catatan tambahan untuk triage"></textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="triage-form-actions">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn-cancel">
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    Simpan Triage
                </button>
            </div>
        </form>
    </div>
</div>
