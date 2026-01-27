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
        // Membuat kolom return_date di tabel loans menjadi nullable
        // Karena tanggal pengembalian belum diketahui saat peminjaman
        Schema::table('loans', function (Blueprint $table) {
            $table->date('return_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Mengembalikan kolom return_date menjadi NOT NULL jika rollback
        Schema::table('loans', function (Blueprint $table) {
            $table->date('return_date')->nullable(false)->change();
        });
    }
};