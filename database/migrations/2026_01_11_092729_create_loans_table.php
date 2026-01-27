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
        // Membuat tabel loans untuk menyimpan data peminjaman barang
        // Schema::create('loans', function (Blueprint $table) {
        //     // Primary key auto-increment
        //     $table->id();

        //     // Timestamps untuk created_at dan updated_at
        //     $table->timestamps();

        //     // Kode unik untuk identifikasi peminjaman (format: LNN-YYYYMMDD-XXX)
        //     $table->string('loan_code')->unique();

        //     // Tanggal peminjaman
        //     $table->date('loan_date');

        //     // Data peminjam
        //     // Nama peminjam (requester)
        //     $table->string('requesters_name');

        //     // Departemen peminjam
        //     $table->string('department');

        //     // Tujuan peminjaman (opsional)
        //     $table->text('purpose')->nullable();

        //     // Status approval peminjaman
        //     $table->enum('status', [
        //         'pending',   // Menunggu approval
        //         'approved',  // Sudah diapprove supervisor
        //         'rejected',  // Ditolak supervisor
        //         'returned'   // Barang sudah dikembalikan
        //     ])->default('pending');

        //     // ID admin yang mencatat peminjaman (foreign key ke users)
        //     $table->foreignId('admin_id')
        //         ->constrained('users')  // Relasi ke tabel users
        //         ->cascadeOnDelete();    // Jika user dihapus, hapus juga loan

        //     // ID supervisor yang menyetujui (foreign key ke users, nullable karena belum diapprove)
        //     $table->foreignId('approved_by')
        //         ->nullable()           // Boleh null jika belum diapprove
        //         ->constrained('users') // Relasi ke tabel users
        //         ->nullOnDelete();      // Jika user dihapus, set null

        //     // Timestamp kapan diapprove
        //     $table->timestamp('approved_at')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel loans jika rollback migration
        // Schema::dropIfExists('loans');
    }
};
