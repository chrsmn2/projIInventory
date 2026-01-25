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
        Schema::table('outgoing_items', function (Blueprint $table) {
            if (Schema::hasColumn('outgoing_items', 'destination')) {
                $table->dropColumn('destination');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_items', function (Blueprint $table) {
            $table->string('destination')->nullable();
        });
    }
};
