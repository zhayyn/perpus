<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Nama kategori (Fiksi, Sains, dll)
            $table->string('slug')->unique(); // URL-friendly name
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6366f1'); // Warna label
            $table->string('icon')->nullable(); // Icon kategori
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
