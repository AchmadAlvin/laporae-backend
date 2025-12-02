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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('kategori', ['Keamanan', 'Aksesibilitas', 'Fasilitas Rusak']);
            $table->string('lokasi');
            $table->string('foto');
            $table->enum('status', ['Baru Masuk', 'Sedang Diverifikasi', 'Selesai Ditindaklanjuti'])->default('Baru Masuk');
            $table->unsignedBigInteger('pelapor_id')->nullable();
            $table->foreign('pelapor_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
