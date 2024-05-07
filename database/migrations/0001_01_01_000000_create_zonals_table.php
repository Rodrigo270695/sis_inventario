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
        Schema::create('zonals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 40);
            $table->enum('unidad_negocio', ['ADMINISTRACION','DISTRIBUIDORA', 'FRANQUICIA', 'DAM', 'PROACTIVO', 'REACTIVO']);
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->unique(['nombre', 'unidad_negocio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonals');
    }
};
