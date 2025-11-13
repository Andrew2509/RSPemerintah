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
        Schema::table('patients', function (Blueprint $table) {
            // ID Rekam Medis Unik Nasional (format: RS-YYYYMMDD-XXXX)
            $table->string('national_mrn')->unique()->nullable()->after('medical_record_number')
                ->comment('ID Rekam Medis Unik Nasional');
            
            // Profil Keluarga & Penanggung Jawab
            $table->string('family_name')->nullable()->after('occupation')
                ->comment('Nama Keluarga/Penanggung Jawab');
            $table->string('family_relationship')->nullable()
                ->comment('Hubungan dengan pasien (Suami/Istri/Ayah/Ibu/Anak/dll)');
            $table->string('family_phone')->nullable()
                ->comment('No. Telepon Keluarga');
            $table->string('family_address')->nullable()
                ->comment('Alamat Keluarga');
            $table->string('family_nik')->nullable()
                ->comment('NIK Keluarga/Penanggung Jawab');
            
            // Kontak Gawat Darurat
            $table->string('emergency_contact_name')->nullable()
                ->comment('Nama Kontak Gawat Darurat');
            $table->string('emergency_contact_relationship')->nullable()
                ->comment('Hubungan dengan pasien');
            $table->string('emergency_contact_phone')->nullable()
                ->comment('No. Telepon Gawat Darurat');
            $table->string('emergency_contact_phone2')->nullable()
                ->comment('No. Telepon Gawat Darurat Alternatif');
            
            // Histori Alergi & Riwayat Penyakit Kronis (diperluas)
            $table->text('chronic_diseases')->nullable()->after('medical_history')
                ->comment('Riwayat Penyakit Kronis (Diabetes, Hipertensi, dll)');
            $table->text('allergy_details')->nullable()->after('allergies')
                ->comment('Detail Alergi (Obat, Makanan, Lainnya)');
            $table->date('last_allergy_incident')->nullable()
                ->comment('Tanggal Insiden Alergi Terakhir');
            
            // QR Patient Wristband
            $table->string('qr_code')->unique()->nullable()
                ->comment('QR Code untuk Patient Wristband');
            $table->string('wristband_number')->unique()->nullable()
                ->comment('Nomor Wristband');
            $table->dateTime('wristband_issued_at')->nullable()
                ->comment('Tanggal Wristband Diterbitkan');
            $table->boolean('wristband_active')->default(false)
                ->comment('Status Aktif Wristband');
            
            // Tracking Rujukan Digital Telemedis
            $table->dateTime('telemedicine_referral_received_at')->nullable()
                ->comment('Waktu Menerima Rujukan Digital');
            $table->dateTime('telemedicine_expected_arrival')->nullable()
                ->comment('Waktu Kedatangan yang Diharapkan');
            $table->dateTime('telemedicine_actual_arrival')->nullable()
                ->comment('Waktu Kedatangan Aktual');
            $table->enum('telemedicine_arrival_status', ['on_time', 'late', 'not_arrived'])->nullable()
                ->comment('Status Kedatangan (Tepat Waktu/Terlambat/Tidak Datang)');
            $table->text('telemedicine_arrival_notes')->nullable()
                ->comment('Catatan Kedatangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'national_mrn',
                'family_name',
                'family_relationship',
                'family_phone',
                'family_address',
                'family_nik',
                'emergency_contact_name',
                'emergency_contact_relationship',
                'emergency_contact_phone',
                'emergency_contact_phone2',
                'chronic_diseases',
                'allergy_details',
                'last_allergy_incident',
                'qr_code',
                'wristband_number',
                'wristband_issued_at',
                'wristband_active',
                'telemedicine_referral_received_at',
                'telemedicine_expected_arrival',
                'telemedicine_actual_arrival',
                'telemedicine_arrival_status',
                'telemedicine_arrival_notes',
            ]);
        });
    }
};
