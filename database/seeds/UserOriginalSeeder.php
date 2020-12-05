<?php

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Profession;
use App\Models\Skill;
use App\Models\Team;
use Illuminate\Database\Seeder;

class UserOriginalSeeder extends Seeder
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

    // 1-14 - Seeders con el Modelo
    // $professionId = Profession::whereTitle('Desarrollador back-end')->value('id');

    /* User::create([
      'name'  => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
      'profession_id' => $professionId,
      'is_admin' => true,
      'created_at' => now(),
    ]);

    // 1-17 - Relaciones del ORM de Eloquent
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

    // 2-11 - Reutilizar las plantillas con la directiva @include
    // 2-22 - Listado avanzado de usuarios
    $professions = Profession::all();
    $skills = Skill::all();
    $teams = Team::all();

    // 2-04 - Selects dinámicos
    $user = factory(User::class)->create([
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
      // 'is_admin' => true,
      'role' => 'admin',
      // 'created_at' => now(),
      'created_at' => now()->addDay(),    // 1 día mas
      'team_id' => $teams->firstWhere('name', 'Styde'),
    ]);

    $user->profile()->create([
      'bio' => 'Programador, editor',
      'twitter' => 'https://twitter.com/superadmin',
      // 'profession_id' => $professionId,

      // 2-22 - Listado avanzado de usuarios
      // 'profession_id' => $professions->where('title','Desarrollador back-end')->first()->id,
      'profession_id' => $professions->firstWhere('title', 'Desarrollador back-end')->id,
    ]);

    // Crear un perfil por cada usuario creado
    /* factory(User::class, 19)->create()->each(function ($user) {
      $user->profile()->create(
        factory(UserProfile::class)->raw()
      );
    }); */

    // Crear un perfil por cada usuario creado
    /* factory(User::class)->times(99)->create()->each(function ($user) use ($professions, $skills) {
      $randomSkills = $skills->random(rand(0, 7));  // Adiconar entre 0 y 7

      $user->skills()->attach($randomSkills);

      // 2-21 Paginación
      // No crear Usuarios adicionales dentro del factory UserProfile
      // y evitar que se ejecute el factory(User::class) dentro del
      // UserProfileFactory -  'user_id' => factory(User::class),
      factory(UserProfile::class)->create([
        'user_id' => $user->id,
        // 2/3 de los perfiles tengan profesión y el otro 1/3 no
        'profession_id' => rand(0, 2) ? $professions->random()->id : null,
      ]);
    }); */

    // 2-24 - Creación y asociación de tablas y modelos
    // Crear 999 Usuarios, asociar el Equipo y las Habilidades de
    // forma aleatoria y crear el Perfil asociado a dicho Usuario
    foreach (range(1, 999) as $i) {
      $user = factory(User::class)->create([
        'team_id' => rand(0, 2) ? null : $teams->random()->id,
      ]);

      $user->skills()->attach($skills->random((rand(0, 7))));

      factory(UserProfile::class)->create([
        'user_id' => $user->id,
        // 2/3 de los perfiles tengan profesión y el otro 1/3 no
        'profession_id' => rand(0, 2) ? $professions->random()->id : null,
      ]);
    };
  }
}
