<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');                    // Judul buku
            $table->string('author');                   // Pengarang
            $table->string('publisher')->nullable();    // Penerbit
            $table->string('isbn', 20)->unique()->nullable(); // ISBN
            $table->integer('published_year')->nullable();    // Tahun terbit
            $table->text('description')->nullable();          // Sinopsis
            $table->string('cover_image')->nullable();        // Foto sampul
            $table->string('language', 10)->default('id');   // Bahasa
            $table->integer('total_pages')->nullable();       // Jumlah halaman
            $table->decimal('price', 10, 2)->nullable();      // Harga buku
            $table->string('location')->nullable();           // Lokasi rak (contoh: A-01-3)
            $table->string('barcode')->unique()->nullable();  // Barcode buku
            $table->string('qr_code')->nullable();            // Path QR code image
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // Soft delete agar data bisa dipulihkan

            $table->fullText(['title', 'author', 'description']); // Untuk pencarian canggih
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
