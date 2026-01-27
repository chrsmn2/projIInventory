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
        // Menghapus kolom borrow_date dari tabel loans
        // Karena sudah ada kolom loan_date yang lebih tepat
        // DICOMMENT karena borrow_date mungkin sudah tidak ada
        Schema::table('loans', function (Blueprint $table) {
            //$table->dropColumn('borrow_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menambahkan kembali kolom borrow_date jika rollback
        // DICOMMENT karena tidak dijalankan
        Schema::table('loans', function (Blueprint $table) {
            //$table->unsignedBigInteger('boorrow_date')->nullable(); // TYPO: seharusnya borrow_date
        });
    }
};
