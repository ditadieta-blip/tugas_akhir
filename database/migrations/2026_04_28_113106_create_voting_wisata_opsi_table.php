<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('voting_wisata_opsi', function (Blueprint $table) {
            $table->id('id_opsi');
            $table->enum('jenis_opsi', ['lokasi', 'tanggal']);
            $table->string('nilai_opsi');
            $table->integer('jumlah_vote')->default(0);
            $table->timestamps();

            // Relasi ke voting utama
            $table->foreignId('id_voting')->constrained('voting_wisata', 'id_voting')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voting_wisata_opsi');
    }
};
