<?php

use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
  public function run()
  {
    // DB::insert('INSERT INTO professions (title) VALUES ("Desarrollador back-end")');   // No recomendado
    // DB::insert('INSERT INTO professions (title) VALUES (?)', ['Desarrollador back-end']);

    // Consultas Sql
    /* DB::insert('INSERT INTO professions (title) VALUES (:title)', [
      'title' => 'Desarrollador back-end',
    ]); */

    // Constructor de consultas Sql de Laravel
    DB::table('professions')->insert([
      ['title' => 'Desarrollador back-end'],
      ['title' => 'Desarrollador front-end'],
      ['title' => 'Diseñador Web'],
    ]);
  }
}