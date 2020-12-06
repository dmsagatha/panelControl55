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

      // Si se elimina un Usuario, que se elimine la asociación
      // con las habilidades
      $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
      $table->foreignId('skill_id')->constrained();

      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('user_skill');
  }
}
