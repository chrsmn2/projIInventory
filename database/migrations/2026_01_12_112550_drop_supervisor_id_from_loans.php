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
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'supervisor_id')) {
                $table->dropForeign(['supervisor_id']);
            }
                if (Schema::hasColumn('loans', 'supervisor_id')) {
                $table->dropColumn('supervisor_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('supervisor_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }
};
