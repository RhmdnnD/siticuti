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
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID ASN yang mengajukan
            $table->string('jenis_cuti'); // Tahunan, Sakit, dll
            $table->text('alasan')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi'); // Berapa hari
            $table->text('alamat_cuti')->nullable();
            $table->string('status')->default('Menunggu'); // Menunggu, Disetujui, Ditolak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
