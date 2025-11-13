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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            
            // Identitas Dasar
            $table->string('medical_record_number')->unique()->comment('Nomor Rekam Medis');
            $table->string('nik', 16)->unique()->nullable()->comment('NIK');
            $table->string('name');
            $table->enum('gender', ['L', 'P'])->comment('Laki-laki/Perempuan');
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            
            // Kategori Pasien
            $table->enum('category', [
                'umum',
                'bpjs',
                'asuransi_swasta',
                'program_pemerintah',
                'sisrute',
                'telemedis'
            ])->default('umum');
            
            // Informasi BPJS
            $table->string('bpjs_number')->nullable()->comment('Nomor BPJS');
            $table->string('bpjs_class')->nullable()->comment('Kelas BPJS (1/2/3)');
            $table->date('bpjs_active_until')->nullable();
            
            // Informasi Asuransi Swasta
            $table->string('insurance_company')->nullable()->comment('Nama Perusahaan Asuransi');
            $table->string('insurance_policy_number')->nullable()->comment('Nomor Polis');
            $table->string('insurance_card_number')->nullable()->comment('Nomor Kartu Asuransi');
            
            // Informasi Program Pemerintah
            $table->enum('government_program', ['jampersal', 'jamkesda', 'other'])->nullable();
            $table->string('government_program_number')->nullable();
            
            // Informasi SISRUTE (Rujukan)
            $table->string('referral_number')->nullable()->comment('Nomor Rujukan SISRUTE');
            $table->string('referring_hospital')->nullable()->comment('Rumah Sakit Pengirim');
            $table->text('referral_reason')->nullable()->comment('Alasan Rujukan');
            $table->dateTime('referral_date')->nullable();
            
            // Informasi Telemedis
            $table->boolean('is_telemedicine')->default(false);
            $table->string('telemedicine_platform')->nullable()->comment('Platform yang digunakan');
            $table->text('telemedicine_notes')->nullable();
            
            // Jenis Layanan
            $table->enum('service_type', ['urj', 'uri', 'ugd'])->nullable()
                ->comment('Unit Rawat Jalan, Rawat Inap, atau UGD');
            
            // Status & Informasi Tambahan
            $table->enum('status', ['active', 'inactive', 'deceased'])->default('active');
            $table->text('allergies')->nullable()->comment('Alergi');
            $table->text('medical_history')->nullable()->comment('Riwayat Medis');
            $table->text('notes')->nullable()->comment('Catatan Tambahan');
            
            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index('category');
            $table->index('service_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
