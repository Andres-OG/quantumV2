<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->char('id_institution', 36)->primary(); // UUID como clave primaria
            $table->string('name')->unique();
            $table->float('payment')->nullable();
            $table->boolean('status')->default(false);
            $table->string('ColorP', 7)->nullable();
            $table->string('ColorS', 7)->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('institutions');
    }
};
