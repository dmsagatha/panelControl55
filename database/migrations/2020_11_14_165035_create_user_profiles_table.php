<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
  public function up()
  {
    Schema::create('user_profiles', function (Blueprint $table) {
      $table->id();

      $table->foreignId('user_id')->constrained();
      $table->foreignId('profession_id')->nullable()->constrained();

      $table->string('bio', 1000);
      $table->string('twitter')->nullable();
      $table->timestamps();
    });
  }
  
  public function down()
  {
    Schema::dropIfExists('user_profiles');
  }
}