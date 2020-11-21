<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('role');
      $table->string('email')->unique();
      $table->softDeletes();
      $table->string('password');
      $table->rememberToken();
      $table->timestamps();
    });
  }
  
  public function down()
  {
    Schema::dropIfExists('users');
  }
}