<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi_senam', function (Blueprint $table) {
            $table->id('id_absensi');

            $table->unsignedBigInteger('id_senam');
            $table->unsignedBigInteger('id_user');

            $table->enum('status', ['hadir', 'tidak'])->nullable();
            $table->boolean('is_confirmed')->default(false);

            $table->timestamps();

            // FK
            $table->foreign('id_senam')->references('id_senam')->on('senam')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');

            // biar ga double
            $table->unique(['id_senam', 'id_user']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_senam');
    }
};
