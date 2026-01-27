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
        // Menambahkan kolom purpose ke tabel loans
        // Tujuan peminjaman
        // DICOMMENT karena sudah ada di migration create_loans_table
        Schema::table('loans', function (Blueprint $table) {
            //$table->string('purpose')->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom purpose jika rollback
        // DICOMMENT karena tidak dijalankan
        Schema::table('loans', function (Blueprint $table) {
            //$table->dropColumn('purpose');
        });
    }
};
