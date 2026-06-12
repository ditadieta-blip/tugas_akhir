<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_wisata', function (Blueprint $table) {
            $table->id('id_pembayaran_wisata');

            $table->unsignedBigInteger('id_daftar_wisata');

            $table->bigInteger('jumlah_bayar');
            $table->bigInteger('total_terbayar')->default(0);
            $table->bigInteger('sisa_tagihan');

            $table->integer('cicilan_ke')->default(1);

            $table->enum('status', ['pending', 'cicilan', 'lunas', 'failed'])
                  ->default('pending');

            // MIDTRANS
            $table->string('midtrans_order_id')->nullable();
            $table->text('midtrans_snap_token')->nullable();

            $table->timestamps();

            // RELASI
            $table->foreign('id_daftar_wisata')
                  ->references('id_daftar_wisata')
                  ->on('pendaftaran_wisata')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_wisata');
    }
};
