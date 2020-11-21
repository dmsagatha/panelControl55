<?php

namespace Tests\Browser\Admin;

use App\Models\{User, Profession ,Skill};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateUserTest extends DuskTestCase
{
  use DatabaseMigrations;

  /** @test */
  function a_user_can_be_created()
  {
    $profession = factory(Profession::class)->create();
    $skillA = factory(Skill::class)->create();
    $skillB = factory(Skill::class)->create();

    $this->browse(function (Browser $browser, $browser2) use ($profession, $skillA, $skillB) {
        $browser->visit('/usuarios/nuevo')
            ->type('first_name', 'Super')
            ->type('last_name', 'Admin')
            ->type('email', 'superadmin@admin.net')
            ->type('password', 'superadmin')
            ->type('bio', 'Programador')
            ->select('profession_id', $profession->id)
            ->type('twitter', 'https://twitter.com/superadmin')
            ->check("skills[{$skillA->id}]")
            ->check("skills[{$skillB->id}]")
            ->radio('role', 'user')
            ->press('Crear usuario')
            ->assertPathIs('/usuarios')   // Redirigir
            ->assertSee('Super Admin')
            ->assertSee('superadmin@admin.net');
    });

    $this->assertCredentials([
        'first_name' => 'Super',
        'last_name'  => 'Admin',
        'email' => 'superadmin@admin.net',
        'password' => 'superadmin',
        'role' => 'user',
    ]);

    $user = User::findByEmail('superadmin@admin.net');

    $this->assertDatabaseHas('user_profiles', [
        'bio' => 'Programador',
        'twitter' => 'https://twitter.com/superadmin',
        'user_id' => $user->id,
        'profession_id' => $profession->id,
    ]);

    $this->assertDatabaseHas('user_skill', [
        'user_id'  => $user->id,
        'skill_id' => $skillA->id,
    ]);

    $this->assertDatabaseHas('user_skill', [
        'user_id'  => $user->id,
        'skill_id' => $skillB->id,
    ]);
  }
}