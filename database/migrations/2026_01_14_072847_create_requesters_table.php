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
        // Membuat tabel requesters untuk menyimpan data peminjam/pemohon
        // Schema::create('requesters', function (Blueprint $table) {
        //     // Primary key auto-increment
        //     $table->id();

        //     // Nama peminjam (requester)
        //     $table->string('requester_name');

        //     // Departemen peminjam
        //     $table->string('department');

        //     // Nomor telepon kontak (maksimal 20 karakter)
        //     $table->string('contact_phone', 20);

        //     // Email kontak (harus unik)
        //     $table->string('contact_email')->unique();

        //     // Status requester (active/inactive)
        //     $table->enum('status', ['active', 'inactive'])->default('active');

        //     // Timestamps untuk created_at dan updated_at
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel requesters jika rollback migration
        // Schema::dropIfExists('requesters');
    }
};
