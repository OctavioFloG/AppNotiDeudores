<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('client_tokens', function (Blueprint $table) {
            $table->id('id_token');
            $table->unsignedBigInteger('id_cliente');
            $table->string('token', 255)->unique();
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')
                ->references('id_cliente')
                ->on('clients')
                ->onDelete('cascade');

            $table->index('token');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_tokens');
    }
};
