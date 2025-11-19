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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->foreignId('id_cuenta')->constrained('cuentas_por_cobrar')->onDelete('cascade');
            $table->text('mensaje');
            $table->dateTime('fecha_envio');
            $table->string('estado_envio', 50);
            $table->boolean('leido')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
