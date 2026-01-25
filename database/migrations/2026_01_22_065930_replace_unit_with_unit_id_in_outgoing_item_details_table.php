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
        Schema::table('outgoing_item_details', function (Blueprint $table) {
             if (!Schema::hasColumn('outgoing_item_details', 'unit_id')) {
                $table->foreignId('unit_id')
                      ->nullable()
                      ->after('quantity')
                      ->constrained('units')
                      ->nullOnDelete();
             }
            
             if (Schema::hasColumn('incoming_item_details', 'unit')) {
                $table->dropColumn('unit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_item_details', function (Blueprint $table) {
            // rollback unit (varchar)
            $table->string('unit')->nullable()->after('quantity');

            // rollback unit_id
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};
