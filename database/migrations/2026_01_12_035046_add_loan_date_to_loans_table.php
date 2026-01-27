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
        // Menambahkan kolom loan_date ke tabel loans
        // Tanggal peminjaman
        // DICOMMENT karena sudah ada di migration create_loans_table
        Schema::table('loans', function (Blueprint $table) {
           // $table->date('loan_date')->after('loan_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom loan_date jika rollback
        // DICOMMENT karena tidak dijalankan
        Schema::table('loans', function (Blueprint $table) {
           // $table->dropColumn('loan_date');
        });
    }
};
