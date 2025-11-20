<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cuentas_por_cobrar', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_institucion');
            $table->decimal('monto', 12, 2);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->string('estado', 50);
            $table->string('descripcion', 500)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')
                ->references('id_cliente')
                ->on('clients')
                ->onDelete('cascade');

            $table->foreign('id_institucion')
                ->references('id_institucion')
                ->on('institutions')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_por_cobrar');
    }
};
