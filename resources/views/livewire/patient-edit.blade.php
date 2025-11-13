<div class="patient-edit-container">
    <div class="patient-edit-wrapper">
        <!-- Header -->
        <div class="patient-edit-header">
            <div class="patient-edit-header-info">
                <h2>Edit Pasien</h2>
                <p>Ubah informasi pasien: <span>{{ $patient->medical_record_number }}</span></p>
            </div>
            <div class="patient-edit-header-actions">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn-detail">
                    Lihat Detail
                </a>
                <a href="{{ route('patients.index') }}" class="btn-back">
                    Kembali
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert-message {{ session('message_type') === 'error' ? 'error' : 'success' }}">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="update" class="patient-edit-form">
            <!-- Identitas Dasar -->
            <div class="patient-edit-section">
                <h3 class="patient-edit-section-title">Identitas Dasar</h3>
                <div class="patient-edit-grid">
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Nomor Rekam Medis</label>
                        <input type="text" value="{{ $patient->medical_record_number }}" disabled
                            class="patient-edit-input">
                        <p class="patient-edit-help-text">Nomor rekam medis tidak dapat diubah</p>
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">NIK</label>
                        <input type="text" wire:model="nik" maxlength="16"
                            class="patient-edit-input">
                        @error('nik') <span class="patient-edit-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" wire:model="name"
                            class="patient-edit-input">
                        @error('name') <span class="patient-edit-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Jenis Kelamin <span class="required">*</span></label>
                        <select wire:model="gender" class="patient-edit-select">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('gender') <span class="patient-edit-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" wire:model="birth_date"
                            class="patient-edit-input">
                        @error('birth_date') <span class="patient-edit-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Tempat Lahir</label>
                        <input type="text" wire:model="birth_place"
                            class="patient-edit-input">
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">No. Telepon</label>
                        <input type="text" wire:model="phone"
                            class="patient-edit-input">
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Email</label>
                        <input type="email" wire:model="email"
                            class="patient-edit-input">
                        @error('email') <span class="patient-edit-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Pekerjaan</label>
                        <input type="text" wire:model="occupation"
                            class="patient-edit-input">
                    </div>

                    <div class="patient-edit-form-group patient-edit-grid-full">
                        <label class="patient-edit-label">Alamat</label>
                        <textarea wire:model="address" rows="2"
                            class="patient-edit-textarea"></textarea>
                    </div>
                </div>
            </div>

            <!-- Kategori & Layanan -->
            <div class="patient-edit-section">
                <h3 class="patient-edit-section-title">Kategori Pasien & Layanan</h3>
                <div class="patient-edit-grid three-col">
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Kategori Pasien <span class="required">*</span></label>
                        <select wire:model.live="category" class="patient-edit-select">
                            @foreach($categories as $cat)
                                <option value="{{ $cat['value'] }}">{{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                        @error('category') <span class="patient-edit-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Jenis Layanan</label>
                        <select wire:model="service_type" class="patient-edit-select">
                            <option value="">Pilih Layanan</option>
                            @foreach($serviceTypes as $st)
                                <option value="{{ $st['value'] }}">{{ $st['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Status <span class="required">*</span></label>
                        <select wire:model="status" class="patient-edit-select">
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                            <option value="deceased">Meninggal</option>
                        </select>
                        @error('status') <span class="patient-edit-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Field Dinamis Berdasarkan Kategori -->
            @if($category === 'bpjs')
                <div class="patient-edit-category-section bpjs">
                    <h3 class="patient-edit-section-title">Informasi BPJS</h3>
                    <div class="patient-edit-category-grid">
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Nomor BPJS <span class="required">*</span></label>
                            <input type="text" wire:model="bpjs_number"
                                class="patient-edit-input">
                            @error('bpjs_number') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Kelas BPJS <span class="required">*</span></label>
                            <select wire:model="bpjs_class" class="patient-edit-select">
                                <option value="">Pilih Kelas</option>
                                <option value="1">Kelas 1</option>
                                <option value="2">Kelas 2</option>
                                <option value="3">Kelas 3</option>
                            </select>
                            @error('bpjs_class') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Berlaku Sampai <span class="required">*</span></label>
                            <input type="date" wire:model="bpjs_active_until"
                                class="patient-edit-input">
                            @error('bpjs_active_until') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'asuransi_swasta')
                <div class="patient-edit-category-section asuransi">
                    <h3 class="patient-edit-section-title">Informasi Asuransi Swasta</h3>
                    <div class="patient-edit-category-grid two-col">
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Nama Perusahaan Asuransi <span class="required">*</span></label>
                            <input type="text" wire:model="insurance_company"
                                class="patient-edit-input">
                            @error('insurance_company') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Nomor Polis <span class="required">*</span></label>
                            <input type="text" wire:model="insurance_policy_number"
                                class="patient-edit-input">
                            @error('insurance_policy_number') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Nomor Kartu Asuransi</label>
                            <input type="text" wire:model="insurance_card_number"
                                class="patient-edit-input">
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'program_pemerintah')
                <div class="patient-edit-category-section program">
                    <h3 class="patient-edit-section-title">Informasi Program Pemerintah</h3>
                    <div class="patient-edit-category-grid two-col">
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Program <span class="required">*</span></label>
                            <select wire:model="government_program" class="patient-edit-select">
                                <option value="">Pilih Program</option>
                                <option value="jampersal">Jampersal</option>
                                <option value="jamkesda">Jamkesda</option>
                                <option value="other">Lainnya</option>
                            </select>
                            @error('government_program') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Nomor Program <span class="required">*</span></label>
                            <input type="text" wire:model="government_program_number"
                                class="patient-edit-input">
                            @error('government_program_number') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'sisrute')
                <div class="patient-edit-category-section sisrute">
                    <h3 class="patient-edit-section-title">Informasi Rujukan SISRUTE</h3>
                    <div class="patient-edit-category-grid two-col">
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Nomor Rujukan <span class="required">*</span></label>
                            <input type="text" wire:model="referral_number"
                                class="patient-edit-input">
                            @error('referral_number') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Rumah Sakit Pengirim <span class="required">*</span></label>
                            <input type="text" wire:model="referring_hospital"
                                class="patient-edit-input">
                            @error('referring_hospital') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Tanggal Rujukan <span class="required">*</span></label>
                            <input type="datetime-local" wire:model="referral_date"
                                class="patient-edit-input">
                            @error('referral_date') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group patient-edit-category-grid-full">
                            <label class="patient-edit-label">Alasan Rujukan <span class="required">*</span></label>
                            <textarea wire:model="referral_reason" rows="3"
                                class="patient-edit-textarea"></textarea>
                            @error('referral_reason') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'telemedis')
                <div class="patient-edit-category-section telemedis">
                    <h3 class="patient-edit-section-title">Informasi Telemedis</h3>
                    <div class="patient-edit-category-grid two-col">
                        <div class="patient-edit-form-group">
                            <label class="patient-edit-label">Platform Telemedis <span class="required">*</span></label>
                            <input type="text" wire:model="telemedicine_platform"
                                placeholder="Contoh: Zoom, Google Meet, dll"
                                class="patient-edit-input">
                            @error('telemedicine_platform') <span class="patient-edit-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-edit-form-group patient-edit-category-grid-full">
                            <label class="patient-edit-label">Catatan Telemedis</label>
                            <textarea wire:model="telemedicine_notes" rows="3"
                                class="patient-edit-textarea"></textarea>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profil Keluarga & Penanggung Jawab -->
            <div class="patient-edit-section">
                <h3 class="patient-edit-section-title">Profil Keluarga & Penanggung Jawab</h3>
                <div class="patient-edit-grid">
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Nama Keluarga/Penanggung Jawab</label>
                        <input type="text" wire:model="family_name"
                            class="patient-edit-input"
                            placeholder="Nama lengkap keluarga/penanggung jawab">
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Hubungan</label>
                        <select wire:model="family_relationship" class="patient-edit-select">
                            <option value="">Pilih Hubungan</option>
                            <option value="Suami">Suami</option>
                            <option value="Istri">Istri</option>
                            <option value="Ayah">Ayah</option>
                            <option value="Ibu">Ibu</option>
                            <option value="Anak">Anak</option>
                            <option value="Saudara">Saudara</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">NIK Keluarga</label>
                        <input type="text" wire:model="family_nik" maxlength="16"
                            class="patient-edit-input"
                            placeholder="16 digit NIK">
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">No. Telepon Keluarga</label>
                        <input type="text" wire:model="family_phone"
                            class="patient-edit-input">
                    </div>
                    <div class="patient-edit-form-group patient-edit-grid-full">
                        <label class="patient-edit-label">Alamat Keluarga</label>
                        <textarea wire:model="family_address" rows="2"
                            class="patient-edit-textarea"></textarea>
                    </div>
                </div>
            </div>

            <!-- Kontak Gawat Darurat -->
            <div class="patient-edit-section">
                <h3 class="patient-edit-section-title">Kontak Gawat Darurat</h3>
                <div class="patient-edit-grid">
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Nama Kontak Gawat Darurat</label>
                        <input type="text" wire:model="emergency_contact_name"
                            class="patient-edit-input"
                            placeholder="Nama lengkap kontak gawat darurat">
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Hubungan</label>
                        <select wire:model="emergency_contact_relationship" class="patient-edit-select">
                            <option value="">Pilih Hubungan</option>
                            <option value="Suami">Suami</option>
                            <option value="Istri">Istri</option>
                            <option value="Ayah">Ayah</option>
                            <option value="Ibu">Ibu</option>
                            <option value="Anak">Anak</option>
                            <option value="Saudara">Saudara</option>
                            <option value="Teman">Teman</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">No. Telepon Utama</label>
                        <input type="text" wire:model="emergency_contact_phone"
                            class="patient-edit-input"
                            placeholder="No. telepon utama">
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">No. Telepon Alternatif</label>
                        <input type="text" wire:model="emergency_contact_phone2"
                            class="patient-edit-input"
                            placeholder="No. telepon alternatif">
                    </div>
                </div>
            </div>

            <!-- Histori Alergi & Riwayat Penyakit Kronis -->
            <div class="patient-edit-section">
                <h3 class="patient-edit-section-title">Histori Alergi & Riwayat Penyakit Kronis</h3>
                <div class="patient-edit-medical-grid">
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Alergi</label>
                        <textarea wire:model="allergies" rows="2"
                            class="patient-edit-textarea"
                            placeholder="Daftar alergi pasien (jika ada)"></textarea>
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Detail Alergi</label>
                        <textarea wire:model="allergy_details" rows="2"
                            class="patient-edit-textarea"
                            placeholder="Detail alergi (Obat, Makanan, Lainnya)"></textarea>
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Tanggal Insiden Alergi Terakhir</label>
                        <input type="date" wire:model="last_allergy_incident"
                            class="patient-edit-input">
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Riwayat Penyakit Kronis</label>
                        <textarea wire:model="chronic_diseases" rows="3"
                            class="patient-edit-textarea"
                            placeholder="Penyakit kronis (Diabetes, Hipertensi, Jantung, dll)"></textarea>
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Riwayat Medis Lainnya</label>
                        <textarea wire:model="medical_history" rows="3"
                            class="patient-edit-textarea"
                            placeholder="Riwayat penyakit atau operasi sebelumnya"></textarea>
                    </div>
                    <div class="patient-edit-form-group">
                        <label class="patient-edit-label">Catatan Tambahan</label>
                        <textarea wire:model="notes" rows="2"
                            class="patient-edit-textarea"></textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="patient-edit-actions">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn-cancel">
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    Update Pasien
                </button>
            </div>
        </form>
    </div>
</div>
