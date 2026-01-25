<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitIdToIncomingItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incoming_item_details', function (Blueprint $table) {
            // Tambahkan kolom unit_id jika belum ada
            if (!Schema::hasColumn('incoming_item_details', 'unit_id')) {
                $table->foreignId('unit_id')->constrained('units')->onDelete('cascade')->after('quatity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_item_details', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};
