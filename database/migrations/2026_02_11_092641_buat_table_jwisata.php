<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jwisata', function (Blueprint $table) {
            $table->integer('id_wisata')->primary();
            $table->string('nama_wisata');
            $table->string('lokasi_wisata');
            $table->text('keterangan_wisata')->nullable();
            $table->date('tanggal_wisata');
            $table->decimal('biaya_wisata', 15, 2);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jwisata');
    }
};
