<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // book_copies = eksemplar fisik buku (1 buku bisa punya banyak eksemplar)
        // Analogi: Perpustakaan punya 5 eksemplar buku "Laskar Pelangi"
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('copy_code')->unique(); // Kode unik eksemplar (mis: LK-001)
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])
                  ->default('baik');
            $table->enum('status', ['tersedia', 'dipinjam', 'reservasi', 'perbaikan'])
                  ->default('tersedia');
            $table->date('acquired_at')->nullable(); // Tanggal diterima perpustakaan
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};
