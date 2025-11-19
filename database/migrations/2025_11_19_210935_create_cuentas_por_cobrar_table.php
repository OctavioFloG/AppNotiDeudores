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
        Schema::create('cuentas_por_cobrar', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->foreignId('id_cliente')->constrained('clients')->onDelete('cascade');
            $table->foreignId('id_institucion')->constrained('institutions')->onDelete('cascade');
            $table->decimal('monto', 12, 2);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->string('estado', 50);
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas_por_cobrar');
    }
};
