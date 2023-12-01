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
        Schema::create('guia_factura', function (Blueprint $table) {
            $table->bigIncrements('guia_factura_id');
            // feels to relation with guian and factura
            $table->unsignedBigInteger('fk_guia_id')->nullable();
            $table->unsignedBigInteger('fk_factura_id')->nullable();

            // references from guia and factura tables oneto many relation
            $table->foreign('fk_guia_id')->references('guia_id')->on('guia')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('fk_factura_id')->references('factura_id')->on('facturas')->onDelete('set null')->onUpdate('cascade');

            // feels unique
            $table->unique(['fk_guia_id', 'fk_factura_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia_factura');
    }
};
