<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('senam', function (Blueprint $table) {

            // Tambah kolom id_user
            $table->unsignedBigInteger('id_user')->after('id_senam');

            // Foreign key ke tabel user
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');

            // Hapus foreign key id_role dulu (kalau ada)
            $table->dropForeign(['id_role']);

            // Hapus kolom id_role
            $table->dropColumn('id_role');
        });
    }

    public function down()
    {
        Schema::table('senam', function (Blueprint $table) {

            $table->unsignedBigInteger('id_role');

            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
};
