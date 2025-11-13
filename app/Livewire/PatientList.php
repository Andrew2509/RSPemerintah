<?php

namespace App\Livewire;

use App\Enums\PatientCategory;
use App\Enums\ServiceType;
use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class PatientList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $serviceTypeFilter = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'serviceTypeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingServiceTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->serviceTypeFilter = '';
        $this->statusFilter = 'active';
        $this->resetPage();
    }

    public function viewDetail($patientId)
    {
        return $this->redirect(route('patients.show', $patientId), navigate: true);
    }

    public function editPatient($patientId)
    {
        return $this->redirect(route('patients.edit', $patientId), navigate: true);
    }

    public function render()
    {
        $query = Patient::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('medical_record_number', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('bpjs_number', 'like', '%' . $this->search . '%');
            });
        }

        // Category Filter
        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        // Service Type Filter
        if ($this->serviceTypeFilter) {
            $query->where('service_type', $this->serviceTypeFilter);
        }

        // Status Filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        // Jika tidak ada filter status, tampilkan semua (termasuk yang baru dibuat)

        $patients = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => Patient::count(),
            'umum' => Patient::where('category', 'umum')->count(),
            'bpjs' => Patient::where('category', 'bpjs')->count(),
            'asuransi_swasta' => Patient::where('category', 'asuransi_swasta')->count(),
            'program_pemerintah' => Patient::where('category', 'program_pemerintah')->count(),
            'sisrute' => Patient::where('category', 'sisrute')->count(),
            'telemedis' => Patient::where('category', 'telemedis')->count(),
        ];

        return view('livewire.patient-list', [
            'patients' => $patients,
            'stats' => $stats,
            'categories' => PatientCategory::options(),
            'serviceTypes' => ServiceType::options(),
        ]);
    }
}
