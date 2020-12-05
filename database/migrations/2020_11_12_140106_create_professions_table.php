<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionsTable extends Migration
{
  public function up()
  {
    Schema::create('professions', function (Blueprint $table) {
      $table->id();
      $table->string('title', 100)->unique();
      $table->softDeletes();  // deleted_at
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('professions');
  }
}
