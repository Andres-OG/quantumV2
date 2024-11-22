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
            $table->date('dia');
            $table->unsignedBigInteger('idSalon');
            $table->timestamps();

            $table->foreign('idSalon')->references('idSalon')->on('salones')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}
