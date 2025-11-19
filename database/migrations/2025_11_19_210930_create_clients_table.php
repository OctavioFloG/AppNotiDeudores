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
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->foreignId('id_institucion')->constrained('institutions')->onDelete('cascade');
            $table->string('nombre', 255);
            $table->string('telefono', 50);
            $table->string('correo', 255);
            $table->string('direccion', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
