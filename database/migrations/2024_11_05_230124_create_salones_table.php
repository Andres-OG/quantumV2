<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salones', function (Blueprint $table) {
            $table->id('idSalon');
            $table->string('nombre');
            $table->unsignedBigInteger('idPiso');
            $table->timestamps();

            $table->foreign('idPiso')->references('id')->on('pisos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salones');
    }
};
