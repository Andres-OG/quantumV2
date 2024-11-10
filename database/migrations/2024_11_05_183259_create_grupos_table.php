<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id('idGrupo');
            
            $table->string('nombre');
            $table->unsignedBigInteger('idMateria'); // Relación con Materia
            $table->string('periodo');
            $table->timestamps();

            // Definir la clave foránea con Materia
            $table->foreign('idMateria')->references('id')->on('materias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
