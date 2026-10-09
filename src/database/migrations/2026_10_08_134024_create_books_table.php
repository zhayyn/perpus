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
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->string('isbn', 20)->unique()->nullable();
            $table->integer('published_year')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('language', 10)->default('id');
            $table->integer('total_pages')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('location')->nullable(); // Lokasi rak: misal A-01-3
            $table->string('barcode')->unique()->nullable();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
