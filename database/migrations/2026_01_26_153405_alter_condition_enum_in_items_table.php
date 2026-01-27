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
        // Update existing data
        DB::table('items')->where('condition', 'normal')->update(['condition' => 'good']);
        DB::table('items')->where('condition', 'lost')->update(['condition' => 'damaged']);

        Schema::table('items', function (Blueprint $table) {
            $table->enum('condition', ['good', 'damaged'])->default('good')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->enum('condition', ['normal', 'damaged', 'lost'])->default('normal')->change();
        });
    }
};
