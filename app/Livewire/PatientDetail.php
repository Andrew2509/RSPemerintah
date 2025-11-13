<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;

class PatientDetail extends Component
{
    public Patient $patient;

    public function mount($id)
    {
        $this->patient = Patient::with('triageRecords')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.patient-detail');
    }
}
