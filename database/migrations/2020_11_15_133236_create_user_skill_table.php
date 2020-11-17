<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSkillTable extends Migration
{
  public function up()
  {
    Schema::create('user_skill', function (Blueprint $table) {
      $table->id();

      // Si se eliminar un Usuario, que se elimine la asociación
      // las habilidades
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('skill_id')->constrained()->onDelete('cascade');
      
      $table->timestamps();
    });
  }
  
  public function down()
  {
    Schema::dropIfExists('user_skill');
  }
}