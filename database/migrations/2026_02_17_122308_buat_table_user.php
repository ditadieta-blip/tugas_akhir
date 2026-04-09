<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user'); // BIGINT UNSIGNED AUTO INCREMENT

            $table->string('nama_user');
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->string('password');

            // HARUS sama tipe dengan role.id_role
            $table->unsignedBigInteger('id_role');

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_role')
                  ->references('id_role')
                  ->on('role')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
};
