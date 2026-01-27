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
        // Membuat tabel loan_approvals untuk menyimpan data approval peminjaman
        // Terpisah dari tabel loans untuk mendukung multiple approvers
        // Schema::create('loan_approvals', function (Blueprint $table) {
        //     // Primary key auto-increment
        //     $table->id();

        //     // Foreign key ke tabel loans (peminjaman yang diapprove)
        //     $table->foreignId('loan_id')
        //         ->constrained('loans')    // Relasi ke tabel loans
        //         ->onDelete('cascade');    // Hapus approval jika loan dihapus

        //     // Foreign key ke tabel users (supervisor yang approve)
        //     $table->foreignId('supervisor_id')
        //         ->constrained('users')    // Relasi ke tabel users
        //         ->onDelete('cascade');    // Hapus approval jika user dihapus

        //     // Status approval
        //     $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

        //     // Catatan tambahan dari supervisor (opsional)
        //     $table->text('notes')->nullable();

        //     // Timestamp kapan diapprove
        //     $table->timestamp('approved_at')->nullable();

        //     // Timestamps untuk created_at dan updated_at
        //     $table->timestamps();

        //     // Unique constraint: satu loan hanya bisa di-approve sekali oleh satu supervisor
        //     $table->unique(['loan_id', 'supervisor_id']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel loan_approvals jika rollback migration
        // Schema::dropIfExists('loan_approvals');
    }
};
