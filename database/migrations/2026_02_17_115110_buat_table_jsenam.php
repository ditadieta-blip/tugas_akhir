<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('senam', function (Blueprint $table) {
            $table->id('id_senam'); // BIGINT UNSIGNED

            // WAJIB sama dengan role.id_role
            $table->unsignedBigInteger('id_role');

            $table->date('tanggal');
            $table->string('tempat_senam');
            $table->string('keterangan_senam');

            $table->timestamps();

            $table->foreign('id_role')
                  ->references('id_role')
                  ->on('role')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('senam');
    }
};
