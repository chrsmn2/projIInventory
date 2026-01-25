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
            // Menghapus kolom status
            if (Schema::hasColumn('outgoing_items', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_items', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('outgoing_date');
        });
    }
};
