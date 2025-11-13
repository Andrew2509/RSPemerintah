<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('triage_records', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke pasien
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            
            // ESI Level (Emergency Severity Index)
            $table->integer('esi_level')->comment('ESI Level 1-5');
            
            // Prioritas Triage
            $table->enum('priority', [
                'resuscitation',
                'emergent',
                'urgent',
                'less_urgent',
                'non_urgent'
            ])->default('non_urgent');
            
            // Skor GCS (Glasgow Coma Scale) - Total 3-15
            $table->integer('gcs_eye')->nullable()->comment('GCS Eye Response (1-4)');
            $table->integer('gcs_verbal')->nullable()->comment('GCS Verbal Response (1-5)');
            $table->integer('gcs_motor')->nullable()->comment('GCS Motor Response (1-6)');
            $table->integer('gcs_total')->nullable()->comment('GCS Total Score (3-15)');
            
            // Vital Signs dari IoT Sensor
            $table->integer('systolic_bp')->nullable()->comment('Tekanan Darah Sistolik (mmHg)');
            $table->integer('diastolic_bp')->nullable()->comment('Tekanan Darah Diastolik (mmHg)');
            $table->integer('heart_rate')->nullable()->comment('Nadi (bpm)');
            $table->integer('respiratory_rate')->nullable()->comment('Laju Pernapasan (rpm)');
            $table->decimal('oxygen_saturation', 5, 2)->nullable()->comment('Saturasi O₂ (%)');
            $table->decimal('temperature', 4, 1)->nullable()->comment('Suhu Tubuh (°C)');
            
            // Skor Vital Sign
            $table->integer('vital_sign_score')->nullable()->comment('Skor Vital Sign (0-100)');
            
            // Riwayat Alergi Kritis
            $table->boolean('has_critical_allergy')->default(false)->comment('Memiliki alergi kritis');
            $table->text('critical_allergy_details')->nullable()->comment('Detail alergi kritis');
            
            // Consent Emergency Protocol
            $table->boolean('consent_emergency_protocol')->default(false)
                ->comment('Izin tindakan darurat otomatis (pasien kritis tanpa keluarga)');
            $table->text('consent_reason')->nullable()->comment('Alasan consent emergency protocol');
            $table->dateTime('consent_issued_at')->nullable()->comment('Waktu consent diterbitkan');
            
            // Keluhan Utama
            $table->text('chief_complaint')->nullable()->comment('Keluhan utama pasien');
            $table->text('triage_notes')->nullable()->comment('Catatan triage');
            
            // Petugas Triage
            $table->foreignId('triage_by')->nullable()->constrained('users')->onDelete('set null')
                ->comment('Petugas yang melakukan triage');
            
            // Status
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            
            // Metadata
            $table->timestamp('triage_time')->useCurrent()->comment('Waktu triage dilakukan');
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index('patient_id');
            $table->index('esi_level');
            $table->index('priority');
            $table->index('status');
            $table->index('triage_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triage_records');
    }
};
