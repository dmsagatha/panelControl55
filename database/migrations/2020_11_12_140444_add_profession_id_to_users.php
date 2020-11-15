<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfessionIdToUsers extends Migration
{
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->foreignId('profession_id')
          ->after('name')
          ->nullable()
          ->constrained();
    });
  }
  
  public function down()
  {
    //        Schema::table('users', function (Blueprint $table) {
    //            $table->dropForeign(['profession_id']);
    //            $table->dropColumn('profession_id');
    //        });
  }
}