<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function it_shows_the_users_list()
  {
    factory(User::class)->create([
      'name' => 'Jon'
    ]);

    factory(User::class)->create([
      'name' => 'Jane'
    ]);

    $this->get('/usuarios')
        ->assertStatus(200)
        ->assertSee('Listado de usuarios')
        ->assertSee('Jon')
        ->assertSee('Jane');

    $this->assertNotRepeatedQueries();
  }

  /** @test */
  function it_paginates_the_users()
  {
    factory(User::class)->create([
        'name' => 'Tercer Usuario',
        'created_at' => now()->subDays(5),
    ]);

    factory(User::class)->times(12)->create([
        'created_at' => now()->subDays(4),
    ]);

    factory(User::class)->create([
        'name' => 'Decimoséptimo Usuario',
        'created_at' => now()->subDays(2),
    ]);

    factory(User::class)->create([
        'name' => 'Segundo Usuario',
        'created_at' => now()->subDays(6),
    ]);

    factory(User::class)->create([
        'name' => 'Primer Usuario',
        'created_at' => now()->subWeek(),
    ]);

    factory(User::class)->create([
        'name' => 'Decimosexto Usuario',
        'created_at' => now()->subDays(3),
    ]);

    $this->get('/usuarios')
        ->assertStatus(200)
        ->assertSeeInOrder([
            'Decimoséptimo Usuario',
            'Decimosexto Usuario',
            'Tercer Usuario',
        ])
        ->assertDontSee('Segundo Usuario')
        ->assertDontSee('Primer Usuario');

    $this->get('/usuarios?page=2')
        ->assertSeeInOrder([
            'Segundo Usuario',
            'Primer Usuario',
        ])
        ->assertDontSee('Tercer Usuario');
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
  function it_shows_the_deleted_users()
  {
    factory(User::class)->create([
        'name' => 'Jon',
        'deleted_at' => now(),
    ]);

    factory(User::class)->create([
        'name' => 'Jane',
    ]);

    $this->get('/usuarios/papelera')
        ->assertStatus(200)
        ->assertSee(trans('Listado de usuarios en papelera'))
        ->assertSee('Jon')
        ->assertDontSee('Jane');
  }
}