<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pisos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->nullable(false);
            $table->unsignedBigInteger('idEdificio');
            $table->foreign('idEdificio')->references('id')->on('edificios')->onDelete('cascade'); // Define la relación con la tabla edificios
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pisos');
    }
};
