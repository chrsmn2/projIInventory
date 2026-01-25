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
        Schema::table('items', function (Blueprint $table) {
            // Update enum values from normal,damaged,lost to Good,Fair,Poor
            $table->enum('condition', ['Good', 'Fair', 'Poor'])->default('Good')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Revert to original enum values
            $table->enum('condition', ['normal', 'damaged', 'lost'])->default('normal')->change();
        });
    }
};
