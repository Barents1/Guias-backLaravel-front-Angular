<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guia', function (Blueprint $table) {
            $table->bigIncrements('guia_id');
            $table->string('numero_guia', 10)->required()->unique();
            $table->dateTime('fecha_envio')->required();
            $table->string('pais_origen', 100)->required();
            $table->string('nombre_remitente', 100)->required();
            $table->string('direccion_remitente', 100)->required();
            $table->string('telefono_remitente', 50)->nullable();
            $table->string('email_remitente', 50)->nullable();
            $table->string('pais_destino', 100)->required();
            $table->string('nombre_destinatario', 100)->required();
            $table->string('direccion_destinatario', 100)->required();
            $table->string('telefono_destinatario', 100)->nullable();
            $table->string('email_destinatario', 100)->nullable();
            $table->double('total', 18, 2 )->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia');
    }
};
