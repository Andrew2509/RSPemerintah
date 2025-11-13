<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Livewire\Dashboard::class)->name('dashboard');
Route::get('/patients', App\Livewire\PatientList::class)->name('patients.index');
Route::get('/patients/register', App\Livewire\PatientRegistration::class)->name('patients.register');
Route::get('/patients/{id}', App\Livewire\PatientDetail::class)->name('patients.show');
Route::get('/patients/{id}/edit', App\Livewire\PatientEdit::class)->name('patients.edit');
Route::get('/patients/{id}/triage', App\Livewire\TriageForm::class)->name('patients.triage');
Route::get('/qr-scanner', App\Livewire\QRWristbandScanner::class)->name('qr.scanner');
