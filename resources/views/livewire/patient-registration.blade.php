<div class="patient-registration-container">
    <div class="patient-registration-wrapper">
        <!-- Header -->
        <div class="patient-registration-header">
            <h2>Registrasi Pasien Baru</h2>
            <p>Form pendaftaran pasien dengan kategori dinamis</p>
        </div>

        @if (session()->has('message'))
            <div class="alert-message {{ session('message_type') === 'error' ? 'error' : 'success' }}">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="patient-registration-form">
            <!-- Identitas Dasar -->
            <div class="patient-registration-section">
                <h3 class="patient-registration-section-title">Identitas Dasar</h3>
                <div class="patient-registration-grid">
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">NIK</label>
                        <input type="text" wire:model="nik" maxlength="16"
                            class="patient-registration-input">
                        @error('nik') <span class="patient-registration-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" wire:model="name"
                            class="patient-registration-input">
                        @error('name') <span class="patient-registration-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Jenis Kelamin <span class="required">*</span></label>
                        <select wire:model="gender" class="patient-registration-select">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('gender') <span class="patient-registration-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" wire:model="birth_date"
                            class="patient-registration-input">
                        @error('birth_date') <span class="patient-registration-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Tempat Lahir</label>
                        <input type="text" wire:model="birth_place"
                            class="patient-registration-input">
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">No. Telepon</label>
                        <input type="text" wire:model="phone"
                            class="patient-registration-input">
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Email</label>
                        <input type="email" wire:model="email"
                            class="patient-registration-input">
                        @error('email') <span class="patient-registration-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Pekerjaan</label>
                        <input type="text" wire:model="occupation"
                            class="patient-registration-input">
                    </div>

                    <div class="patient-registration-form-group patient-registration-grid-full">
                        <label class="patient-registration-label">Alamat</label>
                        <textarea wire:model="address" rows="2"
                            class="patient-registration-textarea"></textarea>
                    </div>
                </div>
            </div>

            <!-- Kategori & Layanan -->
            <div class="patient-registration-section">
                <h3 class="patient-registration-section-title">Kategori Pasien & Layanan</h3>
                <div class="patient-registration-grid">
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Kategori Pasien <span class="required">*</span></label>
                        <select wire:model.live="category" class="patient-registration-select">
                            @foreach($categories as $cat)
                                <option value="{{ $cat['value'] }}">{{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                        @error('category') <span class="patient-registration-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Jenis Layanan</label>
                        <select wire:model="service_type" class="patient-registration-select">
                            <option value="">Pilih Layanan</option>
                            @foreach($serviceTypes as $st)
                                <option value="{{ $st['value'] }}">{{ $st['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Field Dinamis Berdasarkan Kategori -->
            @if($category === 'bpjs')
                <div class="patient-registration-category-section bpjs">
                    <h3 class="patient-registration-section-title">Informasi BPJS</h3>
                    <div class="patient-registration-category-grid">
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Nomor BPJS <span class="required">*</span></label>
                            <input type="text" wire:model="bpjs_number"
                                class="patient-registration-input">
                            @error('bpjs_number') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Kelas BPJS <span class="required">*</span></label>
                            <select wire:model="bpjs_class" class="patient-registration-select">
                                <option value="">Pilih Kelas</option>
                                <option value="1">Kelas 1</option>
                                <option value="2">Kelas 2</option>
                                <option value="3">Kelas 3</option>
                            </select>
                            @error('bpjs_class') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Berlaku Sampai <span class="required">*</span></label>
                            <input type="date" wire:model="bpjs_active_until"
                                class="patient-registration-input">
                            @error('bpjs_active_until') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'asuransi_swasta')
                <div class="patient-registration-category-section asuransi">
                    <h3 class="patient-registration-section-title">Informasi Asuransi Swasta</h3>
                    <div class="patient-registration-category-grid two-col">
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Nama Perusahaan Asuransi <span class="required">*</span></label>
                            <input type="text" wire:model="insurance_company"
                                class="patient-registration-input">
                            @error('insurance_company') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Nomor Polis <span class="required">*</span></label>
                            <input type="text" wire:model="insurance_policy_number"
                                class="patient-registration-input">
                            @error('insurance_policy_number') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Nomor Kartu Asuransi</label>
                            <input type="text" wire:model="insurance_card_number"
                                class="patient-registration-input">
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'program_pemerintah')
                <div class="patient-registration-category-section program">
                    <h3 class="patient-registration-section-title">Informasi Program Pemerintah</h3>
                    <div class="patient-registration-category-grid two-col">
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Program <span class="required">*</span></label>
                            <select wire:model="government_program" class="patient-registration-select">
                                <option value="">Pilih Program</option>
                                <option value="jampersal">Jampersal</option>
                                <option value="jamkesda">Jamkesda</option>
                                <option value="other">Lainnya</option>
                            </select>
                            @error('government_program') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Nomor Program <span class="required">*</span></label>
                            <input type="text" wire:model="government_program_number"
                                class="patient-registration-input">
                            @error('government_program_number') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'sisrute')
                <div class="patient-registration-category-section sisrute">
                    <h3 class="patient-registration-section-title">Informasi Rujukan SISRUTE</h3>
                    <div class="patient-registration-category-grid two-col">
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Nomor Rujukan <span class="required">*</span></label>
                            <input type="text" wire:model="referral_number"
                                class="patient-registration-input">
                            @error('referral_number') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Rumah Sakit Pengirim <span class="required">*</span></label>
                            <input type="text" wire:model="referring_hospital"
                                class="patient-registration-input">
                            @error('referring_hospital') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Tanggal Rujukan <span class="required">*</span></label>
                            <input type="datetime-local" wire:model="referral_date"
                                class="patient-registration-input">
                            @error('referral_date') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group patient-registration-category-grid-full">
                            <label class="patient-registration-label">Alasan Rujukan <span class="required">*</span></label>
                            <textarea wire:model="referral_reason" rows="3"
                                class="patient-registration-textarea"></textarea>
                            @error('referral_reason') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($category === 'telemedis')
                <div class="patient-registration-category-section telemedis">
                    <h3 class="patient-registration-section-title">Informasi Telemedis</h3>
                    <div class="patient-registration-category-grid two-col">
                        <div class="patient-registration-form-group">
                            <label class="patient-registration-label">Platform Telemedis <span class="required">*</span></label>
                            <input type="text" wire:model="telemedicine_platform"
                                placeholder="Contoh: Zoom, Google Meet, dll"
                                class="patient-registration-input">
                            @error('telemedicine_platform') <span class="patient-registration-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="patient-registration-form-group patient-registration-category-grid-full">
                            <label class="patient-registration-label">Catatan Telemedis</label>
                            <textarea wire:model="telemedicine_notes" rows="3"
                                class="patient-registration-textarea"></textarea>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profil Keluarga & Penanggung Jawab -->
            <div class="patient-registration-section">
                <h3 class="patient-registration-section-title">Profil Keluarga & Penanggung Jawab</h3>
                <div class="patient-registration-grid">
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Nama Keluarga/Penanggung Jawab</label>
                        <input type="text" wire:model="family_name"
                            class="patient-registration-input"
                            placeholder="Nama lengkap keluarga/penanggung jawab">
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Hubungan</label>
                        <select wire:model="family_relationship" class="patient-registration-select">
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
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">NIK Keluarga</label>
                        <input type="text" wire:model="family_nik" maxlength="16"
                            class="patient-registration-input"
                            placeholder="16 digit NIK">
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">No. Telepon Keluarga</label>
                        <input type="text" wire:model="family_phone"
                            class="patient-registration-input">
                    </div>
                    <div class="patient-registration-form-group patient-registration-grid-full">
                        <label class="patient-registration-label">Alamat Keluarga</label>
                        <textarea wire:model="family_address" rows="2"
                            class="patient-registration-textarea"></textarea>
                    </div>
                </div>
            </div>

            <!-- Kontak Gawat Darurat -->
            <div class="patient-registration-section">
                <h3 class="patient-registration-section-title">Kontak Gawat Darurat</h3>
                <div class="patient-registration-grid">
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Nama Kontak Gawat Darurat</label>
                        <input type="text" wire:model="emergency_contact_name"
                            class="patient-registration-input"
                            placeholder="Nama lengkap kontak gawat darurat">
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Hubungan</label>
                        <select wire:model="emergency_contact_relationship" class="patient-registration-select">
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
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">No. Telepon Utama</label>
                        <input type="text" wire:model="emergency_contact_phone"
                            class="patient-registration-input"
                            placeholder="No. telepon utama">
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">No. Telepon Alternatif</label>
                        <input type="text" wire:model="emergency_contact_phone2"
                            class="patient-registration-input"
                            placeholder="No. telepon alternatif">
                    </div>
                </div>
            </div>

            <!-- Informasi Medis -->
            <div class="patient-registration-section">
                <h3 class="patient-registration-section-title">Histori Alergi & Riwayat Penyakit Kronis</h3>
                <div class="patient-registration-medical-grid">
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Alergi</label>
                        <textarea wire:model="allergies" rows="2"
                            class="patient-registration-textarea"
                            placeholder="Daftar alergi pasien (jika ada)"></textarea>
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Detail Alergi</label>
                        <textarea wire:model="allergy_details" rows="2"
                            class="patient-registration-textarea"
                            placeholder="Detail alergi (Obat, Makanan, Lainnya)"></textarea>
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Tanggal Insiden Alergi Terakhir</label>
                        <input type="date" wire:model="last_allergy_incident"
                            class="patient-registration-input">
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Riwayat Penyakit Kronis</label>
                        <textarea wire:model="chronic_diseases" rows="3"
                            class="patient-registration-textarea"
                            placeholder="Penyakit kronis (Diabetes, Hipertensi, Jantung, dll)"></textarea>
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Riwayat Medis Lainnya</label>
                        <textarea wire:model="medical_history" rows="3"
                            class="patient-registration-textarea"
                            placeholder="Riwayat penyakit atau operasi sebelumnya"></textarea>
                    </div>
                    <div class="patient-registration-form-group">
                        <label class="patient-registration-label">Catatan Tambahan</label>
                        <textarea wire:model="notes" rows="2"
                            class="patient-registration-textarea"></textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="patient-registration-actions">
                <button type="button" wire:click="$reset" class="btn-reset">
                    Reset
                </button>
                <button type="submit" class="btn-submit">
                    Simpan Pasien
                </button>
            </div>
        </form>
    </div>
</div>
