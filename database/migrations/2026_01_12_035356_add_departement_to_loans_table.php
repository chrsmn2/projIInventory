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
        // Menambahkan kolom department ke tabel loans
        // Departemen peminjam
        // DICOMMENT karena sudah ada di migration create_loans_table
        Schema::table('loans', function (Blueprint $table) {
            //$table->string('department')->after('borrower_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom department jika rollback
        // DICOMMENT karena tidak dijalankan
        Schema::table('loans', function (Blueprint $table) {
            //$table->dropColumn('department');
        });
    }
};
