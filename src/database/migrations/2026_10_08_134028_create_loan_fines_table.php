<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_loan_id')->constrained()->cascadeOnDelete();
            $table->integer('overdue_days');           // Jumlah hari terlambat
            $table->decimal('fine_per_day', 10, 2);   // Denda per hari
            $table->decimal('total_fine', 10, 2);     // Total denda
            $table->enum('payment_status', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->timestamp('paid_at')->nullable();  // Kapan denda dibayar
            $table->foreignId('paid_by')->nullable()->constrained('users'); // Petugas penerima bayar
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_fines');
    }
};
