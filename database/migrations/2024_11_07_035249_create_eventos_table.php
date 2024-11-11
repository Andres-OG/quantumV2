<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('dia', ['lunes', 'martes', 'miércoles', 'jueves', 'viernes']);
            $table->unsignedBigInteger('salon_id'); // Cambié el nombre del campo a salon_id

            $table->foreign('salon_id')->references('idSalon')->on('salones')->onDelete('cascade'); // Apunta a idSalon
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}
