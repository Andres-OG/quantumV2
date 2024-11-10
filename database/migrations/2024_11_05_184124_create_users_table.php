<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id("id_user");
            $table->string('name');
            $table->string('firstNameMale');
            $table->string('firstNameFemale');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('id_institution'); 
            $table->foreign('id_institution')->references('id_institution')->on('institutions')->onDelete('cascade');
            $table->unsignedBigInteger('id_role'); 
            $table->foreign('id_role')->references('id_role')->on('roles')->onDelete('cascade');
            $table->boolean('status')->default(false); 
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
