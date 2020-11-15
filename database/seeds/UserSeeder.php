<?php

use App\Models\{User, UserProfile, Profession};
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

    /* User::create([
      'name'  => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
      'profession_id' => $professionId,
      'is_admin' => true,
      'created_at' => now(),
    ]);

    // 117 - Relaciones del ORM de Eloquent
    User::create([
      'name'  => 'Agatha',
      'email' => 'agatha@tmp.com',
      'password' => bcrypt('agatha'),
      'profession_id' => $professionId,
    ]);

    // 118 - Model Factories
    factory(User::class)->create([
      'profession_id' => $professionId,
    ]); */

    // 204 - Selects dinámicos
    $user = factory(User::class)->create([
      'name'  => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
      // 'is_admin' => true,
      'role' => 'admin',
      'created_at' => now(),
    ]);

    $user->profile()->create([
      'bio' => 'Programador, editor',
      'profession_id' => $professionId,
    ]);

    // Crear un perfil por cada usuario creado
    factory(User::class)->times(29)->create()->each(function ($user) {
      $user->profile()->create(
        factory(UserProfile::class)->raw()
      );
    });
  }
}