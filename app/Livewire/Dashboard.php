<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\TriageRecord;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalPatientsToday;
    public $totalPatientsYesterday;
    public $patientsTodayChange;
    public $ugdCases;
    public $criticalCases;
    public $latestPatients;
    public $criticalAlerts;
    public $patientFlowData;
    public $bedOccupancy;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Total Pasien Hari Ini
        $this->totalPatientsToday = Patient::whereDate('created_at', today())->count();
        $this->totalPatientsYesterday = Patient::whereDate('created_at', today()->subDay())->count();
        
        if ($this->totalPatientsYesterday > 0) {
            $change = (($this->totalPatientsToday - $this->totalPatientsYesterday) / $this->totalPatientsYesterday) * 100;
            $this->patientsTodayChange = round($change, 1);
        } else {
            $this->patientsTodayChange = $this->totalPatientsToday > 0 ? 100 : 0;
        }

        // Kasus UGD
        $this->ugdCases = Patient::where('service_type', 'ugd')
            ->where('status', 'active')
            ->whereDate('created_at', today())
            ->count();

        // Critical Cases (ESI Level 1-2)
        $this->criticalCases = TriageRecord::whereHas('patient', function($query) {
                $query->where('service_type', 'ugd')
                      ->where('status', 'active');
            })
            ->whereIn('esi_level', [1, 2])
            ->where('status', 'active')
            ->count();

        // Pasien Terbaru (5 terakhir)
        $this->latestPatients = Patient::with('triageRecords')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Critical Alerts (Triage dengan ESI 1-2)
        $this->criticalAlerts = TriageRecord::with('patient')
            ->whereIn('esi_level', [1, 2])
            ->where('status', 'active')
            ->orderBy('triage_time', 'desc')
            ->limit(3)
            ->get();

        // Patient Flow Data (7 hari terakhir)
        $this->patientFlowData = $this->getPatientFlowData();

        // Bed Occupancy (simulasi - bisa diambil dari data real jika ada tabel beds)
        $this->bedOccupancy = $this->calculateBedOccupancy();
    }

    private function getPatientFlowData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = Patient::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }
        return $data;
    }

    private function calculateBedOccupancy()
    {
        // Simulasi BOR - bisa diambil dari data real jika ada tabel beds/rooms
        // Untuk sekarang, kita hitung berdasarkan pasien rawat inap aktif
        $totalBeds = 100; // Asumsi total tempat tidur
        $occupiedBeds = Patient::where('service_type', 'uri')
            ->where('status', 'active')
            ->count();
        
        $bor = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;
        $empty = 100 - $bor;
        $maintenance = 4; // Asumsi 4% untuk maintenance
        
        return [
            'bor' => $bor,
            'occupied' => $occupiedBeds,
            'empty' => max(0, $empty - $maintenance),
            'maintenance' => $maintenance,
            'total' => $totalBeds
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
