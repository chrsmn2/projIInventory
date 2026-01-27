<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel requesters untuk menyimpan data peminjam/pemohon (versi terbaru)
        // Schema::create('requesters', function (Blueprint $table) {
        //     // Primary key auto-increment
        //     $table->id();

        //     // Nama peminjam (harus unik)
        //     $table->string('requester_name')->unique();

        //     // ID departemen (foreign key ke tabel departement, nullable)
        //     $table->foreignId('departement_id')
        //         ->nullable()
        //         ->constrained('departement', 'id')  // Relasi ke tabel departement
        //         ->cascadeOnDelete();                 // Jika departement dihapus, hapus requester

        //     // Email kontak (nullable, harus unik jika diisi)
        //     $table->string('contact_email')->nullable()->unique();

        //     // Nomor telepon kontak (nullable)
        //     $table->string('contact_phone')->nullable();

        //     // Status requester (active/inactive)
        //     $table->enum('status', ['active', 'inactive'])->default('active');

        //     // Timestamps untuk created_at dan updated_at
        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        // Menghapus tabel requesters jika rollback migration
        // Schema::dropIfExists('requesters');
    }
};