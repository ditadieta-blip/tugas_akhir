<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_wisata', function (Blueprint $table) {

            $table->id('id_daftar_wisata');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_wisata');

            $table->string('status_daftar')->default('menunggu');

            $table->timestamps();

            // FOREIGN KEY KE USER (FIX)
            $table->foreign('id_user')
                ->references('id_user')   // ⬅ GANTI DI SINI
                ->on('user')
                ->onDelete('cascade');

            // FOREIGN KEY KE JWISATA
            $table->foreign('id_wisata')
                ->references('id_wisata')
                ->on('jwisata')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_wisata');
    }
};