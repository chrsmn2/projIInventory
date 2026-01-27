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
        // Mengubah kolom purpose di tabel loans menjadi nullable
        // Karena tujuan peminjaman boleh kosong/tidak wajib diisi
        // Schema::table('loans', function (Blueprint $table) {
        //     $table->text('purpose')->nullable()->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan kolom purpose menjadi NOT NULL jika rollback
        // Schema::table('loans', function (Blueprint $table) {
        //     $table->text('purpose')->nullable(false)->change();
        // });
    }
};
