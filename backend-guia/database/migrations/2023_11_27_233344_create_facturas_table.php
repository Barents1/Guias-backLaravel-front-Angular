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
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('factura_id');
            $table->string('establecimiento', 3)->required();
            $table->string('punto_emision', 3)->required();
            $table->integer('secuencial')->required();
            $table->dateTime('fecha_emision')->required();
            $table->double('sub_total',  18,  2 )->required();
            $table->double('impuesto',  18,  2 )->required();
            $table->double('total',  18, 2 )->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
