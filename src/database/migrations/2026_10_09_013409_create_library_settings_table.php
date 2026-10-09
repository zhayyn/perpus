<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean
            $table->string('label');                   // Nama tampilan di UI
            $table->text('description')->nullable();
            $table->string('group')->default('umum'); // Grup pengaturan
            $table->timestamps();
        });

        // Seed pengaturan default
        DB::table('library_settings')->insert([
            [
                'key'         => 'fine_per_day',
                'value'       => '1000',
                'type'        => 'integer',
                'label'       => 'Denda per Hari (Rp)',
                'description' => 'Nominal denda keterlambatan per hari dalam Rupiah',
                'group'       => 'peminjaman',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'max_loan_days',
                'value'       => '7',
                'type'        => 'integer',
                'label'       => 'Maksimal Hari Pinjam',
                'description' => 'Jumlah hari maksimal peminjaman buku',
                'group'       => 'peminjaman',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'max_loan_books',
                'value'       => '3',
                'type'        => 'integer',
                'label'       => 'Maksimal Buku Dipinjam',
                'description' => 'Jumlah buku yang bisa dipinjam sekaligus per anggota',
                'group'       => 'peminjaman',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'library_name',
                'value'       => 'Perpustakaan Digital',
                'type'        => 'string',
                'label'       => 'Nama Perpustakaan',
                'description' => 'Nama resmi perpustakaan (tampil di header dan laporan)',
                'group'       => 'umum',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'library_address',
                'value'       => '',
                'type'        => 'string',
                'label'       => 'Alamat Perpustakaan',
                'description' => 'Alamat lengkap perpustakaan untuk keperluan laporan',
                'group'       => 'umum',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('library_settings');
    }
};
