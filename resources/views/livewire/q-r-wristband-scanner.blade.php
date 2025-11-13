<div class="qr-scanner-container">
    <div class="qr-scanner-wrapper">
        <!-- Header -->
        <div class="qr-scanner-header">
            <div class="qr-scanner-header-info">
                <h2>QR Wristband Scanner</h2>
                <p>Scan QR code dari wristband pasien untuk melihat informasi atau tracking tindakan</p>
            </div>
            <div class="qr-scanner-header-actions">
                <a href="{{ route('dashboard') }}" class="btn-back">
                    Kembali
                </a>
            </div>
        </div>

        <div class="qr-scanner-content">
            <!-- Scanner Section -->
            <div class="qr-scanner-section">
                <h3 class="qr-scanner-section-title">Scanner QR Code</h3>
                
                <div class="qr-scanner-camera-container">
                    <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                </div>

                <!-- Manual Input -->
                <div class="qr-scanner-manual-input">
                    <label class="qr-scanner-label">Atau masukkan QR code secara manual:</label>
                    <div class="qr-scanner-input-group">
                        <input type="text" 
                            id="manual-qr-input" 
                            class="qr-scanner-input" 
                            placeholder="Masukkan QR code atau nomor wristband"
                            wire:keydown.enter="scanQRCode($event.target.value)">
                        <button type="button" 
                            class="btn-scan"
                            onclick="scanManual()">
                            Scan
                        </button>
                    </div>
                </div>

                @if($error)
                <div class="qr-scanner-error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $error }}
                </div>
                @endif
            </div>

            <!-- Result Section -->
            @if($showResult && $patient)
            <div class="qr-scanner-result">
                <h3 class="qr-scanner-section-title">Hasil Scan</h3>
                
                <div class="qr-scanner-patient-card">
                    <div class="qr-scanner-patient-header">
                        <div class="qr-scanner-patient-info">
                            <h4>{{ $patient->full_name }}</h4>
                            <p>MRN: {{ $patient->medical_record_number }}</p>
                            @if($patient->wristband_number)
                            <p>Wristband: <strong>{{ $patient->wristband_number }}</strong></p>
                            @endif
                        </div>
                        @if($patient->wristband_active)
                        <span class="qr-scanner-status-badge active">Aktif</span>
                        @else
                        <span class="qr-scanner-status-badge inactive">Nonaktif</span>
                        @endif
                    </div>

                    <div class="qr-scanner-patient-details">
                        <div class="qr-scanner-detail-item">
                            <label>Kategori:</label>
                            <span>
                                @php
                                    $categoryValue = is_string($patient->category) ? $patient->category : $patient->category->value;
                                    $categoryEnum = \App\Enums\PatientCategory::tryFrom($categoryValue);
                                    $categoryLabel = $categoryEnum ? $categoryEnum->label() : ucfirst(str_replace('_', ' ', $categoryValue));
                                @endphp
                                {{ $categoryLabel }}
                            </span>
                        </div>
                        @if($patient->service_type)
                        <div class="qr-scanner-detail-item">
                            <label>Jenis Layanan:</label>
                            <span>
                                @php
                                    $serviceValue = is_string($patient->service_type) ? $patient->service_type : $patient->service_type->value;
                                    $serviceEnum = \App\Enums\ServiceType::tryFrom($serviceValue);
                                    $serviceLabel = $serviceEnum ? $serviceEnum->label() : ucfirst(str_replace('_', ' ', $serviceValue));
                                @endphp
                                {{ $serviceLabel }}
                            </span>
                        </div>
                        @endif
                        @if($patient->age)
                        <div class="qr-scanner-detail-item">
                            <label>Usia:</label>
                            <span>{{ $patient->age }} tahun</span>
                        </div>
                        @endif
                        @if($patient->allergies || $patient->allergy_details)
                        <div class="qr-scanner-detail-item warning">
                            <label>⚠ Alergi:</label>
                            <span style="color: #dc2626;">{{ $patient->allergy_details ?? $patient->allergies }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="qr-scanner-actions">
                        <button type="button" wire:click="resetScan" class="btn-reset">
                            Scan Lagi
                        </button>
                        <button type="button" wire:click="goToPatientDetail" class="btn-detail">
                            Lihat Detail Lengkap
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;
    let isScanning = false;

    document.addEventListener('DOMContentLoaded', function() {
        html5QrCode = new Html5Qrcode("qr-reader");
        
        // Start scanning when page loads
        startScanning();
    });

    function startScanning() {
        if (isScanning) return;
        
        html5QrCode.start(
            { facingMode: "environment" }, // Use back camera
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanError
        ).then(() => {
            isScanning = true;
        }).catch(err => {
            console.error("Unable to start scanning", err);
            // Fallback to manual input if camera not available
            document.getElementById('qr-reader').innerHTML = 
                '<div style="padding: 20px; text-align: center; color: #6b7280;">' +
                'Kamera tidak tersedia. Silakan gunakan input manual di bawah.' +
                '</div>';
        });
    }

    function stopScanning() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
            }).catch(err => {
                console.error("Unable to stop scanning", err);
            });
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning when QR code is found
        stopScanning();
        
        // Send to Livewire
        @this.scanQRCode(decodedText);
    }

    function onScanError(errorMessage) {
        // Ignore errors, keep scanning
    }

    function scanManual() {
        const input = document.getElementById('manual-qr-input');
        const qrCode = input.value.trim();
        
        if (qrCode) {
            @this.scanQRCode(qrCode);
            input.value = '';
        }
    }

    // Stop scanning when component is removed
    document.addEventListener('livewire:before-unload', function() {
        stopScanning();
    });
</script>
