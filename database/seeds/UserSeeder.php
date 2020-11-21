<?php

use App\Models\{User, UserProfile, Profession, Skill, Team};
use Illuminate\Database\Seeder;

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
    foreach(range(1, 19) as $i) {
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
        'first_name' => 'Super',
        'last_name'  => 'Admin',
        'email' => 'superadmin@admin.net',
        'password' => bcrypt('superadmin'),
        'role' => 'admin',
        'created_at' => now()->addDay(),    // 1 día mas
        'team_id' => $this->teams->firstWhere('name', 'Styde'),
    ]);

    $admin->skills()->attach($this->skills);

    $admin->profile()->create([
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
    ]);

    $user->skills()->attach($this->skills->random(rand(0, 7)));

    factory(UserProfile::class)->create([
        'user_id' => $user->id,
        'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
    ]);
  }
}