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
        Schema::table('incoming_item_details', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')
                ->nullable()
                ->after('quantity')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_item_details', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')
                ->nullable()
                ->after('item_id')
                ->change();
        });
    }
};
