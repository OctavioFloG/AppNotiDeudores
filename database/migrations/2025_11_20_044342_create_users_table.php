<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_institucion');
            $table->string('rol', 50);
            $table->string('usuario', 100)->unique();
            $table->string('contrasena_hash', 255);
            $table->timestamps();

            // Foreign key explícita
            $table->foreign('id_institucion')
                ->references('id_institucion')
                ->on('institutions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
