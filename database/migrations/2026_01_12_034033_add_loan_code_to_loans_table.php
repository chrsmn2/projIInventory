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
        // Menambahkan kolom loan_code ke tabel loans
        // Kode unik untuk identifikasi peminjaman
        // DICOMMENT karena sudah ada di migration create_loans_table
        Schema::table('loans', function (Blueprint $table) {
            //$table->string('loan_code')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom loan_code jika rollback
        // DICOMMENT karena tidak dijalankan
        Schema::table('loans', function (Blueprint $table) {
            //$table->dropColumn('loan_code');
        });
    }
};

