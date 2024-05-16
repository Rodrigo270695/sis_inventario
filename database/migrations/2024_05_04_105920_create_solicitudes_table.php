<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->text('mensaje');
            $table->boolean('aprobacion_local')->default(0);
            $table->boolean('aprobacion_gerencia')->default(0);
            $table->text('observacion_local')->nullable();
            $table->text('observacion_gerencia')->nullable();
            $table->unsignedBigInteger('pdv_id');
            $table->foreign('pdv_id')->references('id')->on('pdvs')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('type_request_id');
            $table->foreign('type_request_id')->references('id')->on('type_requests')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
