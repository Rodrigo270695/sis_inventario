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
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->string('modelo',50)->nullable();
            $table->string('capacidad',8)->nullable();
            $table->string('serie',40)->nullable();
            $table->enum('estado_asignado', [
                'ASIGNADO',
                'NO ASIGNADO'
            ])->default('NO ASIGNADO');
            $table->date('fecha_compra');
            $table->integer('garantia_tienda');
            $table->integer('garantia_marca')->nullable();
            $table->string('codigo_barras', 14)->unique();
            $table->enum('estado', [
                'BAJA',
                'DESECHADO',
                'MANTENIMIENTO-DAÑO',
                'MANTENIMIENTO-GARANTIA',
                'RESERVADO',
                'USO'
                ])->default('USO');
            $table->text('descripcion')->nullable();
            $table->string('documento', 255)->nullable();
            $table->unsignedBigInteger('make_id');
            $table->foreign('make_id')->references('id')->on('makes')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
