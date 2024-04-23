<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('norms', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',70)->unique();
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('make_id');
            $table->foreign('make_id')->references('id')->on('makes')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('norms');
    }
};
