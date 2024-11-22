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
            $table->time('horaInicio'); // Cambiado a tipo time
            $table->time('horaFin'); // Cambiado a tipo time
            $table->enum('dia', ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado']); // Incluyendo sábado
            $table->string('periodo')->default('2024B');
            $table->unsignedBigInteger('idMaestro');
            $table->unsignedBigInteger('idGrupo');
            $table->unsignedBigInteger('idSalon');
            $table->timestamps();

            // Definir las llaves foráneas correctamente
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
