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
        // Mengubah nama kolom borrower_name menjadi requester_name di tabel loans
        // untuk konsistensi penamaan (borrower -> requester)
        // Schema::table('loans', function (Blueprint $table) {
        //     $table->renameColumn('borrower_name', 'requester_name');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan nama kolom requester_name menjadi borrower_name
        // jika rollback migration
        // Schema::table('loans', function (Blueprint $table) {
        //     $table->renameColumn('requester_name', 'borrower_name');
        // });
    }
};
