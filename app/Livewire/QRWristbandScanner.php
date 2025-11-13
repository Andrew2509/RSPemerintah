<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;

class QRWristbandScanner extends Component
{
    public $scannedCode = '';
    public $patient = null;
    public $error = '';
    public $showResult = false;

    public function scanQRCode($qrCode)
    {
        $this->scannedCode = $qrCode;
        $this->error = '';
        $this->patient = null;
        $this->showResult = false;

        // Parse QR code format: PATIENT-{MRN}-{TIMESTAMP}
        if (strpos($qrCode, 'PATIENT-') === 0) {
            // Extract MRN from QR code
            $parts = explode('-', $qrCode);
            if (count($parts) >= 2) {
                $mrn = $parts[1]; // Get MRN from second part
                
                // Find patient by MRN
                $this->patient = Patient::where('medical_record_number', $mrn)
                    ->orWhere('qr_code', $qrCode)
                    ->first();
            }
        } else {
            // Try to find by QR code directly
            $this->patient = Patient::where('qr_code', $qrCode)
                ->orWhere('wristband_number', $qrCode)
                ->orWhere('medical_record_number', $qrCode)
                ->first();
        }

        if ($this->patient) {
            $this->showResult = true;
            $this->error = '';
        } else {
            $this->error = 'Pasien tidak ditemukan. Pastikan QR code wristband valid.';
            $this->showResult = false;
        }
    }

    public function goToPatientDetail()
    {
        if ($this->patient) {
            return $this->redirect(route('patients.show', $this->patient->id), navigate: true);
        }
    }

    public function resetScan()
    {
        $this->scannedCode = '';
        $this->patient = null;
        $this->error = '';
        $this->showResult = false;
    }

    public function render()
    {
        return view('livewire.q-r-wristband-scanner');
    }
}
