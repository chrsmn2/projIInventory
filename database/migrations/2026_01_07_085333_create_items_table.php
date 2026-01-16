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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();  
            $table->string('item_name');            
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete(); // Category relation
            $table->integer('stock')->default(0);   
            $table->integer('min_stock')->default(0); 
            $table->enum('condition',['normal','damaged','lost'])->default('normal'); // Condition
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
