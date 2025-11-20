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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id('id_institucion');
            $table->string('nombre', 255);
            $table->string('direccion', 500)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('correo', 255)->unique();
            $table->timestamps(); // creado_en, actualizado_en
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
