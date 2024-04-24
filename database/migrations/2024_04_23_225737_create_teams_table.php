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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50)->unique();
            $table->string('ip',15)->nullable();
            $table->string('serie',40)->nullable();
            $table->string('procesador',60)->nullable();
            $table->string('mac',30)->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('estado', [
                'BAJA',
                'DESECHADO',
                'MANTENIMIENTO-DAÑO',
                'MANTENIMIENTO-GARANTIA',
                'RESERVADO',
                'USO'
            ])->default('USO');
            $table->date('fecha_compra');
            $table->integer('garantia_tienda');
            $table->integer('garantia_marca')->nullable();
            $table->string('codigo_barras', 100);
            $table->string('documento', 255)->nullable();
            $table->unsignedBigInteger('norm_id');
            $table->foreign('norm_id')->references('id')->on('norms')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('teams');
    }
};
