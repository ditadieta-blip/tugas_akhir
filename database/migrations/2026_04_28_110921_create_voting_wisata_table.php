<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voting_wisata', function (Blueprint $table) {
            $table->id('id_voting');
            $table->string('judul_voting');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('voting_wisata');
    }
};