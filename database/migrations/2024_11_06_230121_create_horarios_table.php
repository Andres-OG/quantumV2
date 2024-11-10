<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id('idHorario');
            $table->string('nombre');
            $table->string('horaInicio');
            $table->string('horaFin');
            $table->string('dia');
            $table->unsignedBigInteger('idMaestro');
            $table->unsignedBigInteger('idGrupo');
            $table->unsignedBigInteger('idSalon');
            $table->timestamps();

            $table->foreign('idMaestro')->references('id')->on('maestros')->onDelete('cascade');
            $table->foreign('idGrupo')->references('idGrupo')->on('grupos')->onDelete('cascade');
            $table->foreign('idSalon')->references('idSalon')->on('salones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
