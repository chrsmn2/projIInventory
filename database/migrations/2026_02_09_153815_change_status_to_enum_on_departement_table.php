<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enum_on_departement', function (Blueprint $table) {
             DB::statement("
            ALTER TABLE departement
            MODIFY status ENUM('active','inactive')
            NOT NULL
            DEFAULT 'active'
        ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enum_on_departement', function (Blueprint $table) {
            DB::statement("
            ALTER TABLE departement
            MODIFY status TINYINT(1)
            NOT NULL
            DEFAULT 1
        ");
        });
    }
};
