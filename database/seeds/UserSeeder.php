<?php

use App\Models\Login;
use Illuminate\Database\Seeder;
use App\Models\{User, UserProfile, Profession, Skill, Team};

class UserSeeder extends Seeder
{
  /**
   * 2-24 - Creación y asociación de tablas y modelos
   */
  protected $professions;
  protected $skills;
  protected $teams;

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->fetchRelations();

    $this->createAdmin();

    /**
     * Crear 999 Usuarios, asociar un Equipo y Habilidades de
     * forma aleatoria y crear el Perfil asociado a dicho Usuario
    */
    foreach(range(1, 39) as $i) {
      $this->createRandomUser();
    }
  }

  protected function fetchRelations()
  {
    $this->professions = Profession::all();
    $this->skills = Skill::all();
    $this->teams = Team::all();
  }

  protected function createAdmin()
  {
    $admin = factory(User::class)->create([
        'name'  => 'Super Admin',
        'email' => 'superadmin@admin.net',
        'password' => bcrypt('superadmin'),
        'role' => 'admin',
        'created_at' => now(), //->addDay(),    // 1 día mas
        'team_id' => $this->teams->firstWhere('name', 'Styde'),
        'active' => true,
    ]);

    $admin->skills()->attach($this->skills);

    $admin->profile->update([
        'bio'     => 'Programador, editor',
        'twitter' => 'https://twitter.com/superadmin',
        'profession_id' => $this->professions->firstWhere('title','Desarrollador back-end')->id,
    ]);
  }

  /**
   * 2-24 - Creación y asociación de tablas y modelos
   * Crear Usuarios, asociar un Equipo y Habilidades de
   * forma aleatoria y crear el Perfil asociado a dicho Usuario
   */
  protected function createRandomUser()
  {
    $user = factory(User::class)->create([
        'team_id' => rand(0, 2) ? null : $this->teams->random()->id,
        'active'  => rand(0, 3) ? true : false,
        'created_at' => now()->subDays(rand(1, 90)),
    ]);

    $user->skills()->attach($this->skills->random(rand(0, 7)));

    /* factory(UserProfile::class)->create([
        'user_id' => $user->id,
        'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
    ]); */

    // 2-40 Actualizar el perfil del usuario ya existente
    $user->profile->update([
      'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
    ]);

    factory(Login::class)->times(rand(1, 10))->create([
        'user_id' => $user->id,
    ]);
  }
}