<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('voting_wisata_detail', function (Blueprint $table) {
            $table->id('id_detail_vote');
            
            // FK ke tabel voting, user, dan opsi
            $table->foreignId('id_voting')->constrained('voting_wisata', 'id_voting')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            $table->foreignId('id_opsi_lokasi')->constrained('voting_wisata_opsi', 'id_opsi')->onDelete('cascade');
            $table->foreignId('id_opsi_tanggal')->constrained('voting_wisata_opsi', 'id_opsi')->onDelete('cascade');

            $table->timestamps();

            // Mencegah user yang sama vote dua kali di voting yang sama
            $table->unique(['id_voting', 'id_user'], 'user_voted_unique');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('voting_wisata_detail');
    }
};
