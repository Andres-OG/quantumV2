<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('name');
            $table->string('firstNameMale')->nullable();
            $table->string('firstNameFemale')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->char('id_institution', 36); // Relación con institutions
            $table->foreign('id_institution')
                  ->references('id_institution')
                  ->on('institutions')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('id_role'); // Relación con roles
            $table->foreign('id_role')
                  ->references('id_role')
                  ->on('roles')
                  ->onDelete('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->string('account_number', 7)->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
