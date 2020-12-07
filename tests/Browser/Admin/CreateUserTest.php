<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use App\Models\Profession;
use App\Models\Skill;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateUserTest extends DuskTestCase
{
  use DatabaseMigrations;

  /** @test */
  public function a_user_can_be_created()
  {
    $profession = Profession::factory()->create();
    $skillA = Skill::factory()->create();
    $skillB = Skill::factory()->create();

    $this->browse(function (Browser $browser) use ($profession, $skillA, $skillB) {
      $browser->visit('/usuarios/nuevo')
            ->type('name', 'Super Admin')
            ->type('email', 'superadmin@admin.net')
            ->type('password', 'superadmin')
            ->type('bio', 'Programador')
            ->select('profession_id', $profession->id)
            ->type('twitter', 'https://twitter.com/superadmin')
            ->check("skills[{$skillA->id}]")
            ->check("skills[{$skillB->id}]")
            ->radio('role', 'user')
            ->radio('state', 'active')
            ->press('Crear usuario')
            ->assertPathIs('/usuarios')   // Redirigir
            ->assertSee('Super Admin')
            ->assertSee('superadmin@admin.net');
    });

    $this->assertCredentials([
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => 'superadmin',
      'role' => 'user',
      'active' => true,
    ]);

    $user = User::findByEmail('superadmin@admin.net');

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador',
      'twitter' => 'https://twitter.com/superadmin',
      'user_id' => $user->id,
      'profession_id' => $profession->id,
    ]);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillA->id,
    ]);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillB->id,
    ]);
  }
}
