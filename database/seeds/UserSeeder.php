<?php

use App\Models\User;
use App\Models\Profession;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  public function run()
  {
    // Sql
    // $professions = DB::select('SELECT id FROM professions WHERE title = "Desarrollador back-end"');   // No recomendado
    // $professions = DB::select('SELECT id FROM professions WHERE title = ?', ['Desarrollador back-end']);

    // Constructor de consultas Sql de Lravel - 
    // take obtener un solo resultado
    // $professions = DB::table('professions')->select('id')->take(1)->get();
    // dd($professions->first()->id);

    // Objeto
    // $profession = DB::table('professions')->select('id')->first();
    // $profession = DB::table('professions')->whereTitle('Desarrollador back-end')->first();
    // $profession = DB::table('professions')->select('id', 'title')->whereTitle('Desarrollador back-end')->first();

    // Obtener el id
    /* $professionId = DB::table('professions')
        ->whereTitle('Desarrollador back-end')
        ->value('id');
    // dd($professionId);

    DB::table('users')->insert([
      'name'  => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
      // 'profession_id' => $professions[0]->id
      // 'profession_id' => $professions->first()->id
      // 'profession_id' => $profession->id
      'profession_id' => $professionId
    ]); */

    // 114 - Seeders con el Modelo
    $professionId = Profession::whereTitle('Desarrollador back-end')->value('id');

    User::create([
      'name'  => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
      'profession_id' => $professionId
    ]);
  }
}