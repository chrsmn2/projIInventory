<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('departement', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('departement_name');
        });

        DB::statement('UPDATE departement SET status = is_active');

        Schema::table('departement', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('departement', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->after('departement_name');
        });

        DB::statement('UPDATE departement SET is_active = status');

        Schema::table('departement', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
