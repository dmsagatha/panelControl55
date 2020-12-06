<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
  public function run()
  {
    // 114 - Seeders con el Modelo
    Profession::create([
      'title' => 'Desarrollador back-end'
    ]);

    Profession::create([
      'title' => 'Desarrollador front-end',
    ]);

    Profession::create([
      'title' => 'Diseñador web',
    ]);

    // 118 - Model Factories
    factory(Profession::class)->times(17)->create();
  }
}
