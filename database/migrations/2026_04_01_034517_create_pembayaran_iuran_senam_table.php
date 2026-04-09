<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_iuran_senam', function (Blueprint $table) {
            $table->id('id_bayar_iuran');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_senam');

            $table->integer('nominal_bayar')->default(2500);

            $table->enum('status', ['pending', 'success', 'failed'])
                  ->default('pending');

            // MIDTRANS
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_snap_token')->nullable();
            $table->string('midtrans_transaction_status')->nullable();

            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();

            // RELASI
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');

            $table->foreign('id_senam')
                  ->references('id_senam')
                  ->on('senam')
                  ->onDelete('cascade');

            // ANTI DOUBLE BAYAR
            $table->unique(['id_user', 'id_senam']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_iuran_senam');
    }
};
