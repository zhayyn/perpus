<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code')->unique(); // Nomor kartu anggota (mis: MBR-2024-001)
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('type', ['siswa', 'mahasiswa', 'umum', 'guru', 'dosen'])
                  ->default('umum');
            $table->string('institution')->nullable(); // Sekolah/Universitas
            $table->string('photo')->nullable();
            $table->string('qr_code')->nullable(); // QR Code kartu anggota
            $table->date('membership_start')->nullable();
            $table->date('membership_end')->nullable(); // Masa berlaku anggota
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
