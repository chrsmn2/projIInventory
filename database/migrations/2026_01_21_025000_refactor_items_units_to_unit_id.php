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
            // Hapus kolom units string
            if (Schema::hasColumn('items', 'units')) {
                $table->dropColumn('units');
            }
            
            // Tambah foreign key ke units table
            if (!Schema::hasColumn('items', 'unit_id')) {
                $table->foreignId('unit_id')->nullable()->after('category_id')->constrained('units')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'unit_id')) {
                $table->dropForeignIdFor('Unit');
                $table->dropColumn('unit_id');
            }
            
            if (!Schema::hasColumn('items', 'units')) {
                $table->string('units')->nullable()->after('stock');
            }
        });
    }
};
