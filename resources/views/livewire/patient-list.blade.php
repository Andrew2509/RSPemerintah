<div class="patient-list-container">
    @if (session()->has('message'))
        <div class="alert {{ session('message_type') === 'error' ? 'alert-error' : 'alert-success' }}">
            {{ session('message') }}
        </div>
    @endif

    <!-- Header Section -->
    <div class="patient-list-header">
        <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
<div>
                <h1>Daftar Pasien</h1>
                <p>Kelola data pasien dengan mudah dan efisien</p>
            </div>
            <a href="{{ route('patients.register') }}" class="btn-register">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Registrasi Pasien Baru
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <p class="stat-card-label">Total</p>
                    <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="stat-card-value">{{ $stats['total'] }}</p>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <p class="stat-card-label">Umum</p>
                    <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="stat-card-value">{{ $stats['umum'] }}</p>
            </div>
            <div class="stat-card blue">
                <div class="stat-card-header">
                    <p class="stat-card-label">BPJS</p>
                    <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <p class="stat-card-value">{{ $stats['bpjs'] }}</p>
            </div>
            <div class="stat-card green">
                <div class="stat-card-header">
                    <p class="stat-card-label">Asuransi</p>
                    <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <p class="stat-card-value">{{ $stats['asuransi_swasta'] }}</p>
            </div>
            <div class="stat-card purple">
                <div class="stat-card-header">
                    <p class="stat-card-label">Program</p>
                    <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <p class="stat-card-value">{{ $stats['program_pemerintah'] }}</p>
            </div>
            <div class="stat-card orange">
                <div class="stat-card-header">
                    <p class="stat-card-label">SISRUTE</p>
                    <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <p class="stat-card-value">{{ $stats['sisrute'] }}</p>
            </div>
            <div class="stat-card indigo">
                <div class="stat-card-header">
                    <p class="stat-card-label">Telemedis</p>
                    <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="stat-card-value">{{ $stats['telemedis'] }}</p>
            </div>
        </div>
    </div>

    <!-- Search and Filters Section -->
    <div class="filter-section">
        <h3>Cari & Filter</h3>
        <div class="filter-grid">
            <div class="filter-group filter-search">
                <label class="filter-label">Cari Pasien</label>
                <div class="search-input-wrapper">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama, NIK, No. RM, atau BPJS..."
                        class="filter-input">
                </div>
            </div>
            <div class="filter-group">
                <label class="filter-label">Kategori</label>
                <select wire:model.live="categoryFilter" class="filter-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat['value'] }}">{{ $cat['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Layanan</label>
                <select wire:model.live="serviceTypeFilter" class="filter-select">
                    <option value="">Semua Layanan</option>
                    @foreach($serviceTypes as $st)
                        <option value="{{ $st['value'] }}">{{ $st['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select wire:model.live="statusFilter" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                    <option value="deceased">Meninggal</option>
                </select>
            </div>
        </div>
        <div class="filter-actions">
            <button wire:click="clearFilters" class="btn-reset">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reset Filter
            </button>
        </div>
    </div>

    <!-- Patient Cards Grid -->
    @if($patients->count() > 0)
        <div class="patients-grid">
            @foreach($patients as $patient)
                @php
                    $categoryValue = is_string($patient->category) ? $patient->category : $patient->category->value;
                    $categoryEnum = \App\Enums\PatientCategory::tryFrom($categoryValue);
                    $categoryLabel = $categoryEnum ? $categoryEnum->label() : ucfirst(str_replace('_', ' ', $categoryValue));

                    $categoryClasses = [
                        'umum' => ['badge' => 'badge-gray', 'avatar' => 'avatar-gray'],
                        'bpjs' => ['badge' => 'badge-blue', 'avatar' => 'avatar-blue'],
                        'asuransi_swasta' => ['badge' => 'badge-green', 'avatar' => 'avatar-green'],
                        'program_pemerintah' => ['badge' => 'badge-purple', 'avatar' => 'avatar-purple'],
                        'sisrute' => ['badge' => 'badge-orange', 'avatar' => 'avatar-orange'],
                        'telemedis' => ['badge' => 'badge-indigo', 'avatar' => 'avatar-indigo'],
                    ];
                    $catClass = $categoryClasses[$categoryValue] ?? $categoryClasses['umum'];

                    $statusClasses = [
                        'active' => 'status-active',
                        'inactive' => 'status-inactive',
                        'deceased' => 'status-deceased',
                    ];
                    $statusClass = $statusClasses[$patient->status] ?? 'status-active';
                    $statusLabels = [
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                        'deceased' => 'Meninggal',
                    ];
                    $statusLabel = $statusLabels[$patient->status] ?? $patient->status;
                @endphp
                <div class="patient-card">
                    <!-- Card Header -->
                    <div class="patient-card-header">
                        <div class="patient-card-header-content">
                            <div class="patient-card-avatar">
                                <div class="patient-card-avatar-icon {{ $catClass['avatar'] }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="patient-card-mr">
                                    <p class="patient-card-mr-label">No. RM</p>
                                    <p class="patient-card-mr-value">{{ $patient->medical_record_number }}</p>
                                </div>
                            </div>
                            <span class="patient-card-category {{ $catClass['badge'] }}">
                                {{ $categoryLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="patient-card-body">
                        <h3 class="patient-card-name">{{ $patient->full_name }}</h3>
                        @if($patient->nik)
                            <p class="patient-card-nik">NIK: {{ $patient->nik }}</p>
                        @endif
                        @if($patient->national_mrn)
                            <p class="patient-card-nik" style="color: #2563eb; font-weight: 600;">
                                ID Nasional: {{ $patient->national_mrn }}
                            </p>
                        @endif
                        @if($patient->wristband_number)
                            <p class="patient-card-nik" style="color: #16a34a; font-weight: 600;">
                                Wristband: {{ $patient->wristband_number }}
                                @if($patient->wristband_active)
                                    <span style="color: #16a34a; font-size: 11px;">● Aktif</span>
                                @else
                                    <span style="color: #dc2626; font-size: 11px;">● Nonaktif</span>
                                @endif
                            </p>
                        @endif
                        @if($patient->qr_code)
                            <p class="patient-card-nik" style="font-family: monospace; font-size: 11px; color: #6b7280;">
                                QR: {{ substr($patient->qr_code, 0, 30) }}...
                            </p>
                        @endif

                        <div class="patient-card-info">
                            <div class="patient-card-info-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>
                                    {{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}
                                    @if($patient->age)
                                        <span style="color: #9ca3af;">({{ $patient->age }} tahun)</span>
                                    @endif
                                </span>
                            </div>
                            @if($patient->service_type)
                                @php
                                    $serviceValue = is_string($patient->service_type) ? $patient->service_type : $patient->service_type->value;
                                    $serviceEnum = \App\Enums\ServiceType::tryFrom($serviceValue);
                                    $serviceLabel = $serviceEnum ? $serviceEnum->label() : ucfirst(str_replace('_', ' ', $serviceValue));
                                @endphp
                                <div class="patient-card-info-item">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span>{{ $serviceLabel }}</span>
                                </div>
                            @endif
                            @if($patient->phone)
                                <div class="patient-card-info-item">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $patient->phone }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="patient-card-footer">
                            <span class="patient-card-status {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                            <span class="patient-card-date">
                                {{ $patient->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Footer - Actions -->
                    <div class="patient-card-actions">
                        <button wire:click="viewDetail({{ $patient->id }})" class="btn-detail">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </button>
                        <button wire:click="editPatient({{ $patient->id }})" class="btn-edit">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($patients->hasPages())
            <div class="pagination-container">
                {{ $patients->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3>Tidak ada data pasien</h3>
            <p>Mulai dengan mendaftarkan pasien baru</p>
            <a href="{{ route('patients.register') }}" class="btn-register" style="margin-top: 24px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Registrasi Pasien Baru
            </a>
        </div>
    @endif
</div>
