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
        // Menghapus kolom supervisor_id dari tabel loans
        // Karena approval supervisor sekarang menggunakan tabel terpisah (loan_approvals)
        // Schema::table('loans', function (Blueprint $table) {
        //     // Cek apakah kolom supervisor_id ada sebelum menghapus foreign key
        //     if (Schema::hasColumn('loans', 'supervisor_id')) {
        //         $table->dropForeign(['supervisor_id']);  // Hapus foreign key constraint
        //     }
        //     // Cek lagi dan hapus kolom
        //     if (Schema::hasColumn('loans', 'supervisor_id')) {
        //         $table->dropColumn('supervisor_id');  // Hapus kolom
        //     }
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menambahkan kembali kolom supervisor_id jika rollback
        // Untuk backward compatibility
        // Schema::table('loans', function (Blueprint $table) {
        //     $table->foreignId('supervisor_id')  // Foreign key ke users
        //           ->nullable()                   // Boleh null
        //           ->constrained('users')         // Relasi ke tabel users
        //           ->nullOnDelete();              // Set null jika user dihapus
        // });
    }
};
