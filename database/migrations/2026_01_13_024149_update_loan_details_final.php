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
        Schema::table('loan_details', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_details', 'returned_quantity')) {
            $table->integer('returned_quantity')->default(0)->after('quantity');
        }

        if (!Schema::hasColumn('loan_details', 'condition_out')) {
            $table->string('condition_out')->nullable()->after('returned_quantity');
        }

        if (!Schema::hasColumn('loan_details', 'condition_in')) {
            $table->string('condition_in')->nullable()->after('condition_out');
        }

        if (!Schema::hasColumn('loan_details', 'status_id')) {
            $table->foreignId('status_id')
                  ->nullable()
                  ->after('condition_in')
                  ->constrained('statuses')
                  ->nullOnDelete();
        }

        if (!Schema::hasColumn('loan_details', 'supervisor_id')) {
            $table->foreignId('supervisor_id')
                  ->nullable()
                  ->after('status_id')
                  ->constrained('users')
                  ->nullOnDelete();
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
        $table->dropForeign(['supervisor_id']);
        $table->dropColumn([
            'returned_quantity',
            'condition_out',
            'condition_in',
            'status_id',
            'supervisor_id',
        ]);
        });
    }
};
