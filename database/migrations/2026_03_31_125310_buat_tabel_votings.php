<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voting', function (Blueprint $table) {

            // PRIMARY KEY custom
            $table->id('id_voting');

            $table->string('judul');

            $table->boolean('is_active')->default(false);

            $table->timestamp('mulai')->nullable();
            $table->timestamp('selesai')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voting');
    }
};