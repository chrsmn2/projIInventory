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
        // Schema::create('loan_details', function (Blueprint $table) {
            /*$table->id();
            Relasi ke loans
            $table->foreignId('loan_id')
                  ->constrained('loans')
                  ->cascadeOnDelete();

            Relasi ke items
            $table->foreignId('item_id')
                  ->constrained('items')
                  ->cascadeOnDelete();

            Jumlah dipinjam
            $table->integer('quantity');

            Jumlah dikembalikan
            if (!Schema::hasColumn('loan_details', 'returned_quantity')) {
                $table->integer('returned_quantity')->default(0);
            }

            if (!Schema::hasColumn('loan_details', 'condition_out')) {
                $table->string('condition_out')->nullable();
            }

            if (!Schema::hasColumn('loan_details', 'condition_in')) {
                $table->string('condition_in')->nullable();
            }

            if (!Schema::hasColumn('loan_details', 'status')) {
                $table->string('status')->default('borrowed');
            }

            if (!Schema::hasColumn('loan_details', 'supervisor_id')) {
                $table->foreignId('supervisor_id')->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();*/
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('loan_details');
    }
};
