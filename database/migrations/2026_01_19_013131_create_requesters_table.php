<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requesters', function (Blueprint $table) {
            $table->id();
            $table->string('requester_name')->unique();
            $table->foreignId('departement_id')->nullable()->constrained('departement', 'id')->cascadeOnDelete();
            $table->string('contact_email')->nullable()->unique();
            $table->string('contact_phone')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requesters');
    }
};