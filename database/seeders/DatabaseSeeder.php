<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run()
  {
    $this->truncateTables([
      'users',
      'user_profiles',
      'user_skill',
      'skills',
      'professions',
      'teams',
    ]);

    $this->call([
      ProfessionSeeder::class,
      SkillSeeder::class,
      TeamSeeder::class,
      UserSeeder::class,
    ]);
  }

  protected function truncateTables(array $tables)
  {
    DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

    foreach ($tables as $table) {
      DB::table($table)->truncate();
    }

    DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
  }
}
