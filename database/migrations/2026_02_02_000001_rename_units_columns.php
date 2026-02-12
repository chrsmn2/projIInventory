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
        Schema::table('units', function (Blueprint $table) {
            $table->renameColumn('code', 'unit_code');
            $table->renameColumn('name', 'unit_name');
            $table->renameColumn('description', 'unit_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->renameColumn('unit_code', 'code');
            $table->renameColumn('unit_name', 'name');
            $table->renameColumn('unit_description', 'description');
        });
    }
};
