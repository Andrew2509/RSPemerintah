<div class="dashboard-container">
    <div class="dashboard-header-section">
        <h2>Dashboard Overview</h2>
        <p>Monitoring real-time aktivitas rumah sakit dan status operasional</p>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <!-- Total Pasien Hari Ini -->
        <div class="kpi-card">
            <div class="kpi-card-content">
                <div class="kpi-card-info">
                    <p class="kpi-card-label">Total Pasien Hari Ini</p>
                    <p class="kpi-card-value">{{ $totalPatientsToday }}</p>
                    <p class="kpi-card-change {{ $patientsTodayChange >= 0 ? 'positive' : 'negative' }}">
                        @if($patientsTodayChange > 0)
                            +{{ $patientsTodayChange }}% dari kemarin
                        @elseif($patientsTodayChange < 0)
                            {{ $patientsTodayChange }}% dari kemarin
                        @else
                            Sama dengan kemarin
                        @endif
                    </p>
                </div>
                <div class="kpi-card-icon-wrapper blue">
                    <svg class="kpi-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- BOR -->
        <div class="kpi-card">
            <div class="kpi-card-content">
                <div class="kpi-card-info">
                    <p class="kpi-card-label">BOR</p>
                    <p class="kpi-card-value">{{ $bedOccupancy['bor'] }}%</p>
                    <p class="kpi-card-change {{ $bedOccupancy['bor'] < 80 ? 'positive' : ($bedOccupancy['bor'] < 90 ? 'neutral' : 'negative') }}">
                        @if($bedOccupancy['bor'] < 80)
                            Kapasitas Normal
                        @elseif($bedOccupancy['bor'] < 90)
                            Kapasitas Tinggi
                        @else
                            Kapasitas Penuh
                        @endif
                    </p>
                </div>
                <div class="kpi-card-icon-wrapper green">
                    <svg class="kpi-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kasus UGD -->
        <div class="kpi-card">
            <div class="kpi-card-content">
                <div class="kpi-card-info">
                    <p class="kpi-card-label">Kasus UGD</p>
                    <p class="kpi-card-value">{{ $ugdCases }}</p>
                    <p class="kpi-card-change {{ $criticalCases > 0 ? 'negative' : 'positive' }}">
                        @if($criticalCases > 0)
                            {{ $criticalCases }} Critical
                        @else
                            Tidak ada kasus kritis
                        @endif
                    </p>
                </div>
                <div class="kpi-card-icon-wrapper red">
                    <svg class="kpi-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Dokter Jaga -->
        <div class="kpi-card">
            <div class="kpi-card-content">
                <div class="kpi-card-info">
                    <p class="kpi-card-label">Pasien Rawat Inap</p>
                    <p class="kpi-card-value">{{ $bedOccupancy['occupied'] }}</p>
                    <p class="kpi-card-change neutral">{{ $bedOccupancy['total'] }} Tempat Tidur</p>
                </div>
                <div class="kpi-card-icon-wrapper purple">
                    <svg class="kpi-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-grid">
        <!-- Alur Pasien Harian -->
        <div class="chart-card">
            <h3 class="chart-card-title">Alur Pasien Harian</h3>
            <div class="chart-placeholder">
                <div class="chart-placeholder-content">
                    <div style="padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            @foreach($patientFlowData as $data)
                            <div style="text-align: center; flex: 1;">
                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">{{ $data['date'] }}</div>
                                <div style="font-size: 18px; font-weight: 600; color: #111827;">{{ $data['count'] }}</div>
                            </div>
                            @endforeach
                        </div>
                        <div style="height: 100px; display: flex; align-items: flex-end; gap: 8px; border-bottom: 2px solid #e5e7eb;">
                            @php
                                $maxCount = max(array_column($patientFlowData, 'count'));
                                $maxCount = $maxCount > 0 ? $maxCount : 1;
                            @endphp
                            @foreach($patientFlowData as $data)
                            <div style="flex: 1; background: #2563eb; height: {{ max(10, ($data['count'] / $maxCount) * 100) }}%; border-radius: 4px 4px 0 0;"></div>
                            @endforeach
                        </div>
                        <p class="chart-placeholder-text" style="margin-top: 12px;">Alur Pasien 7 Hari Terakhir</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Okupansi Tempat Tidur -->
        <div class="chart-card">
            <h3 class="chart-card-title">Okupansi Tempat Tidur</h3>
            <div class="circular-chart-container">
                <div class="circular-chart-wrapper">
                    <div style="position: relative; width: 192px; height: 192px; margin: 0 auto 16px;">
                        <svg class="circular-chart-svg" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" class="circular-chart-bg"></circle>
                            <circle cx="50" cy="50" r="45" class="circular-chart-progress-green"></circle>
                            <circle cx="50" cy="50" r="45" class="circular-chart-progress-orange"></circle>
                        </svg>
                        <div class="circular-chart-center">{{ $bedOccupancy['bor'] }}%</div>
                    </div>
                    <div class="circular-chart-legend">
                        <div class="circular-chart-legend-item">
                            <div class="circular-chart-legend-dot green"></div>
                            <span class="circular-chart-legend-text">Terisi ({{ $bedOccupancy['bor'] }}%)</span>
                        </div>
                        <div class="circular-chart-legend-item">
                            <div class="circular-chart-legend-dot gray"></div>
                            <span class="circular-chart-legend-text">Kosong ({{ $bedOccupancy['empty'] }}%)</span>
                        </div>
                        <div class="circular-chart-legend-item">
                            <div class="circular-chart-legend-dot orange"></div>
                            <span class="circular-chart-legend-text">Maintenance ({{ $bedOccupancy['maintenance'] }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Alerts, Latest Patients, Quick Actions -->
    <div class="bottom-grid">
        <!-- Alert Prioritas Tinggi -->
        <div class="alert-card">
            <h3 class="alert-card-title">Alert Prioritas Tinggi</h3>
            <div class="alert-list">
                @forelse($criticalAlerts as $alert)
                <div class="alert-item {{ $alert->esi_level == 1 ? 'red' : 'orange' }}">
                    <div class="alert-icon-wrapper">
                        <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="alert-content">
                        <p class="alert-title">Pasien Kritis - ESI Level {{ $alert->esi_level }}</p>
                        <p class="alert-description">{{ $alert->patient->full_name }} - {{ $alert->patient->medical_record_number }}</p>
                        <p class="alert-time">{{ $alert->triage_time->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="alert-item" style="background: #d1fae5; border-color: #6ee7b7;">
                    <div class="alert-content">
                        <p class="alert-title" style="color: #065f46;">Tidak ada alert kritis</p>
                        <p class="alert-description" style="color: #047857;">Semua pasien dalam kondisi stabil</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pasien Terbaru -->
        <div class="latest-patients-card">
            <h3 class="alert-card-title">Pasien Terbaru</h3>
            <div class="latest-patients-list">
                @forelse($latestPatients as $patient)
                <div class="patient-item">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($patient->name) }}&background={{ $patient->service_type === 'ugd' ? 'ef4444' : ($patient->service_type === 'uri' ? '3b82f6' : '10b981') }}&color=fff" 
                         class="patient-avatar" alt="{{ $patient->name }}">
                    <div class="patient-info">
                        <p class="patient-name">{{ $patient->full_name }}</p>
                        <p class="patient-category">
                            @php
                                $categoryValue = is_string($patient->category) ? $patient->category : $patient->category->value;
                                $categoryEnum = \App\Enums\PatientCategory::tryFrom($categoryValue);
                                $categoryLabel = $categoryEnum ? $categoryEnum->label() : ucfirst(str_replace('_', ' ', $categoryValue));
                                
                                $serviceValue = $patient->service_type ? (is_string($patient->service_type) ? $patient->service_type : $patient->service_type->value) : '';
                                $serviceEnum = $serviceValue ? \App\Enums\ServiceType::tryFrom($serviceValue) : null;
                                $serviceLabel = $serviceEnum ? $serviceEnum->label() : ($serviceValue ? ucfirst(str_replace('_', ' ', $serviceValue)) : '');
                            @endphp
                            {{ $categoryLabel }}@if($serviceLabel) - {{ $serviceLabel }}@endif
                        </p>
                        @php
                            $latestTriage = $patient->triageRecords->sortByDesc('triage_time')->first();
                        @endphp
                        @if($latestTriage && $latestTriage->esi_level <= 2)
                        <p class="patient-doctor" style="color: #dc2626; font-weight: 600;">
                            ⚠ ESI Level {{ $latestTriage->esi_level }}
                        </p>
                        @endif
                    </div>
                    <a href="{{ route('patients.show', $patient->id) }}" class="patient-action-btn {{ $patient->service_type === 'ugd' ? 'red' : ($patient->service_type === 'uri' ? 'blue' : 'green') }}">
                        DETAIL
                    </a>
                </div>
                @empty
                <div class="patient-item">
                    <div class="patient-info" style="width: 100%; text-align: center; padding: 20px;">
                        <p style="color: #6b7280;">Belum ada pasien terdaftar</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-card">
            <h3 class="alert-card-title">Quick Actions</h3>
            <div class="quick-actions-grid">
                <a href="{{ route('patients.index') }}" class="quick-action-btn blue">
                    <svg class="quick-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="quick-action-label">Daftar Pasien</span>
                </a>
                <a href="{{ route('patients.register') }}" class="quick-action-btn green">
                    <svg class="quick-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="quick-action-label">Registrasi Pasien</span>
                </a>
                <a href="{{ route('qr.scanner') }}" class="quick-action-btn blue">
                    <svg class="quick-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    <span class="quick-action-label">Scan QR</span>
                </a>
                <a href="{{ route('patients.index') }}?status=active&service_type=ugd" class="quick-action-btn orange">
                    <svg class="quick-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="quick-action-label">Kasus UGD</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Status Sistem & Integrasi -->
    <div class="system-status-card">
        <h3 class="alert-card-title">Status Sistem & Integrasi</h3>
        <div class="system-status-grid">
            <!-- Sistem Internal -->
            <div class="system-status-section">
                <h4 class="system-status-section-title">Sistem Internal</h4>
                <div class="system-status-list">
                    <div class="system-status-item">
                        <span class="system-status-label">HIS Core</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot green"></div>
                            <span class="system-status-text green">Online</span>
                        </div>
                    </div>
                    <div class="system-status-item">
                        <span class="system-status-label">Database</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot green"></div>
                            <span class="system-status-text green">Online</span>
                        </div>
                    </div>
                    <div class="system-status-item">
                        <span class="system-status-label">IoT Sensors</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot orange"></div>
                            <span class="system-status-text orange">Partial</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Integrasi Eksternal -->
            <div class="system-status-section">
                <h4 class="system-status-section-title">Integrasi Eksternal</h4>
                <div class="system-status-list">
                    <div class="system-status-item">
                        <span class="system-status-label">BPJS VClaim</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot green"></div>
                            <span class="system-status-text green">Connected</span>
                        </div>
                    </div>
                    <div class="system-status-item">
                        <span class="system-status-label">SIRSUTE</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot green"></div>
                            <span class="system-status-text green">Connected</span>
                        </div>
                    </div>
                    <div class="system-status-item">
                        <span class="system-status-label">SATUSEHAT</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot red"></div>
                            <span class="system-status-text red">Offline</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab Eksternal -->
            <div class="system-status-section">
                <h4 class="system-status-section-title">Lab Eksternal</h4>
                <div class="system-status-list">
                    <div class="system-status-item">
                        <span class="system-status-label">Lab Prodia</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot green"></div>
                            <span class="system-status-text green">Connected</span>
                        </div>
                    </div>
                    <div class="system-status-item">
                        <span class="system-status-label">Lab Kimia Farma</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot green"></div>
                            <span class="system-status-text green">Connected</span>
                        </div>
                    </div>
                    <div class="system-status-item">
                        <span class="system-status-label">Lab Pramita</span>
                        <div class="system-status-indicator">
                            <div class="system-status-dot orange"></div>
                            <span class="system-status-text orange">Slow</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
