<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersModuleTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function it_shows_the_users_list()
  {
    factory(User::class)->create([
      'name' => 'Joel'
    ]);

    factory(User::class)->create([
      'name' => 'Ellie'
    ]);

    $this->get('/usuarios')
        ->assertStatus(200)
        ->assertSee('Listado de usuarios')
        ->assertSee('Joel')
        ->assertSee('Ellie');
  }

  /** @test */
  function it_shows_a_default_message_if_the_users_list_is_empty()
  {
    // Vaciar la prueba antes
    // DB::table('users')->truncate();

    $this->get('/usuarios')
        ->assertStatus(200)
        ->assertSee('No hay usuarios registrados.');
  }
  
  /** @test */
  function it_displays_the_users_details()
  {
    $user = factory(User::class)->create([
        'name' => 'Super Admin'
    ]);

    $this->get('/usuarios/'.$user->id) // usuarios/5
        ->assertStatus(200)
        ->assertSee('Super Admin');
  }

  /** @test */
  function it_displays_a_404_error_if_the_user_is_not_found()
  {
    $this->get('/usuarios/999')
        ->assertStatus(404)
        ->assertSee('Página no encontrada');
  }
  
  /** @test */
  function it_loads_the_new_users_page()
  {
    $this->withoutExceptionHandling();
    
    $this->get('/usuarios/nuevo')
        ->assertStatus(200)
        ->assertSee('Crear usuario');
  }

  /** @test */
  function it_creates_a_new_user()
  {
    $this->withoutExceptionHandling();

    $this->post('/usuarios/', [
        'name'  => 'Super Admin',
        'email' => 'superadmin@admin.net',
        'password' => 'superadmin'
    ])->assertRedirect('usuarios');

    $this->assertCredentials([
        'name'  => 'Super Admin',
        'email' => 'superadmin@admin.net',
        'password' => 'superadmin',
    ]);
  }

  /** @test */
  function the_name_is_required()
  {
    $this->from('usuarios/nuevo')
        ->post('/usuarios/', [
          'name'  => '',
          'email' => 'superadmin@admin.net',
          'password' => 'superadmin'
        ])
        ->assertRedirect('usuarios/nuevo') 
        ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

    //  Comprobar que el usuario no se creo
    $this->assertEquals(0, User::count());
    /* $this->assertDatabaseMissing('users', [
      'email' => 'superadmin@admin.net',
    ]); */
  }

  /** @test */
  function the_email_is_required()
  {
    $this->withExceptionHandling();

    $this->from('usuarios/nuevo')
        ->post('/usuarios/', [
          'name'  => 'Super Admin',
          'email' => '',
          'password' => 'superadmin'
        ])
        ->assertRedirect('usuarios/nuevo') 
        ->assertSessionHasErrors(['email']);

    //  Comprobar que el usuario no se creo
    $this->assertEquals(0, User::count());
  }

  /** @test */
  function the_email_must_be_valid()
  {
    $this->from('usuarios/nuevo')
        ->post('/usuarios/', [
            'name'  => 'Super Admin',
            'email' => 'correo-no-valido',
            'password' => 'superadmin'
        ])
        ->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['email']);

    $this->assertEquals(0, User::count());
  }

  /** @test */
  function the_email_must_be_unique()
  {
    factory(User::class)->create([
        'email' => 'superadmin@admin.net'
    ]);

    $this->from('usuarios/nuevo')
        ->post('/usuarios/', [
            'name'  => 'Super Admin',
            'email' => 'superadmin@admin.net',
            'password' => 'superadmin'
        ])
        ->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['email']);

    $this->assertEquals(1, User::count());
  }

  /** @test */
  function the_password_is_required()
  {
    // $this->withExceptionHandling();
    
    $this->from('usuarios/nuevo')
        ->post('/usuarios/', [
          'name'  => 'Super Admin',
          'email' => 'superadmin@admin.net',
          'password' => ''
        ])
        ->assertRedirect('usuarios/nuevo') 
        ->assertSessionHasErrors(['password']);

    //  Comprobar que el usuario no se creo
    $this->assertEquals(0, User::count());
  }

  /** @test */
  function it_loads_the_edit_user_page()
  {
    $this->withoutExceptionHandling();

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

    // $this->withoutExceptionHandling();

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
}