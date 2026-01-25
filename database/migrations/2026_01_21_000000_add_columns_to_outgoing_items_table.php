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
            $table->string('code')->unique()->after('id');
            $table->foreignId('departement_id')->nullable()->after('supervisor_id')->constrained('departement')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_items', function (Blueprint $table) {
            $table->dropForeign(['departement_id']);
            $table->dropColumn('departement_id');
            $table->dropColumn('code');
        });
    }
};
