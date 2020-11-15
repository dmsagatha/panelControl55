<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUsersTest extends TestCase
{
  use RefreshDatabase;

  protected $defaultData = [
      'name'  => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => 'superadmin',
      'role' => 'user',
      'bio'      => 'Programador de Laravel y Vue.js',
      'twitter'  => 'https://twitter.com/superadmin',
      'profession_id' => '',
  ];

  /** @test */
  function it_loads_the_edit_user_page()
  {
    $user = factory(User::class)->create();

    $this->get("/usuarios/{$user->id}/editar") // usuarios/5/editar
        ->assertStatus(200)
        ->assertViewIs('users.edit')
        ->assertSee('Editar usuario')
        ->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->id === $user->id;
        });
  }

  /** @test */
  function it_updates_a_user()
  {
    $user = factory(User::class)->create();

    $this->put("/usuarios/{$user->id}", [
        'name'  => 'Super Admin',
        'email' => 'superadmin@admin.net',
        'password' => 'superadmin'

    ])->assertRedirect("/usuarios/{$user->id}");

    $this->assertCredentials([
        'name'  => 'Super Admin',
        'email' => 'superadmin@admin.net',
        'password' => 'superadmin',
    ]);
  }

  /** @test */
  function the_name_is_required()
  {
    $this->handleValidationExceptions();
    
    $user = factory(User::class)->create();

    $this->from("usuarios/{$user->id}/editar")
        ->put("usuarios/{$user->id}", [
          'name'  => '',
          'email' => 'superadmin@admin.net',
          'password' => 'superadmin'
        ])
        ->assertRedirect("usuarios/{$user->id}/editar")
        ->assertSessionHasErrors(['name']);

    $this->assertDatabaseMissing('users', ['email' => 'superadmin@admin.net']);
  }

  /** @test */
  function the_email_must_be_valid()
  {
    $this->handleValidationExceptions();
    
    $user = factory(User::class)->create();

    $this->from("usuarios/{$user->id}/editar")
        ->put("usuarios/{$user->id}", [
          'name'  => 'Super Admin',
          'email' => 'correo-no-valido',
          'password' => 'superadmin'
        ])
        ->assertRedirect("usuarios/{$user->id}/editar")
        ->assertSessionHasErrors(['email']);

    $this->assertDatabaseMissing('users', ['name' => 'Super Admin']);
  }

  /** @test */
  function the_email_must_be_unique()
  {
    $this->handleValidationExceptions();
    
    factory(User::class)->create([
        'email' => 'existing-email@example.com',
    ]);

    $user = factory(User::class)->create([
        'email' => 'superadmin@admin.net'
    ]);

    $this->from("usuarios/{$user->id}/editar")
        ->put("usuarios/{$user->id}", [
            'name'  => 'Super Admin',
            'email' => 'existing-email@example.com',
            'password' => 'superadmin'
        ])
        ->assertRedirect("usuarios/{$user->id}/editar")
        ->assertSessionHasErrors(['email']);
  }

  /** @test */
  function the_users_email_can_stay_the_same()
  {
    $user = factory(User::class)->create([
        'email' => 'superadmin@admin.net'
    ]);

    $this->from("usuarios/{$user->id}/editar")
        ->put("usuarios/{$user->id}", [
            'name'  => 'Super Admin',
            'email' => 'superadmin@admin.net',
            'password' => 'superadmin'
        ])
        ->assertRedirect("usuarios/{$user->id}"); // (users.show)

    $this->assertDatabaseHas('users', [
        'name'  => 'Super Admin',
        'email' => 'superadmin@admin.net',
    ]);
  }

  protected function getValidData(array $custom = [])
  {
    return array_merge([
        'name'  => 'Super Admin',
        'email' => 'superadmin@admin.net',
        'password' => 'superadmin',
        'role' => 'user',
        'profession_id' => '',
        'bio'      => 'Programador de Laravel y Vue.js',
        'twitter'  => 'https://twitter.com/superadmin',
    ], $custom);
  }
}