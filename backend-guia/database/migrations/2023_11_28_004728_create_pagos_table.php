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
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('pago_id');
            // If there are only 3 types of payments
            $table->enum('tipo_pago', ['Efectivo', 'Tarjeta', 'Transferencia'])->required();
            $table->double('valor', 18, 2 )->required();
            // feel to relation with facturas
            $table->unsignedBigInteger('fk_factura_id')->nullable();
            // oneto many relation with facturas table
        $table->foreign('fk_factura_id')->references('factura_id')->on('facturas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
