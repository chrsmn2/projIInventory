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
        Schema::create('incoming_item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_item_id')->constrained('incoming_items', 'id')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items', 'id')->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_item_details');
    }
};
