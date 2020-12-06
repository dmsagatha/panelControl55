<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginsTable extends Migration
{
  public function up()
  {
    Schema::create('logins', function (Blueprint $table) {
      $table->id();

      // Al eliminar un Usuario, eliminar los registros de
      // inicio de sesión
      $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('logins');
  }
}
