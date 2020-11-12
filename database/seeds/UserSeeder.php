<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  public function run()
  {
    DB::table('users')->insert([
      'name'  => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
    ]);
  }
}