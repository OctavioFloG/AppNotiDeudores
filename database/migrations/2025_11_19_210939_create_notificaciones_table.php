<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->unsignedBigInteger('id_institucion')->nullable();
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_cuenta')->nullable(); // CuentaPorCobrar
            $table->enum('tipo', ['email', 'sms', 'whatsapp', 'push'])->default('email');
            $table->text('mensaje');
            $table->text('asunto')->nullable(); // Para emails
            $table->enum('estado', ['pendiente', 'enviada', 'fallida', 'leida'])->default('pendiente');
            $table->string('destinatario')->nullable(); // Email o teléfono
            $table->text('respuesta_error')->nullable(); // Error si falla
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamps();
            
            $table->foreign('id_institucion')->references('id_institucion')->on('institutions')->onDelete('cascade');
            $table->foreign('id_cuenta')->references('id_cuenta')->on('cuentas_por_cobrar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
