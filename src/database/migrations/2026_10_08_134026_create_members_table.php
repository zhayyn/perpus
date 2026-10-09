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
            $table->string('member_code')->unique();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('type', ['siswa', 'mahasiswa', 'umum', 'guru', 'dosen'])->default('umum');
            $table->string('institution')->nullable();
            $table->string('photo')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->date('membership_start')->nullable();
            $table->date('membership_end')->nullable();
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
