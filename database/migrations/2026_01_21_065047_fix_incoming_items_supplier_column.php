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
        Schema::table('incoming_items', function (Blueprint $table) {
            // Drop the old suppliers column and add supplier_id as foreign key
            $table->dropColumn('supplier');
            $table->foreignId('supplier_id')->after('admin_id')->constrained('suppliers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_items', function (Blueprint $table) {
            // Reverse: drop supplier_id and add back suppliers column
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
            $table->string('supplier')->after('admin_id');
        });
    }
};
