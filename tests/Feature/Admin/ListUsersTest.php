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
        // ->assertSee('Listado de usuarios')
        ->assertSee(trans('users.title.index'))
        ->assertSee('Jon')
        ->assertSee('Jane');

    //$this->assertNotRepeatedQueries();
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
  function users_are_ordered_by_name()
  {
    factory(User::class)->create(['name' => 'John Doe']);
    factory(User::class)->create(['name' => 'Richard Roe']);
    factory(User::class)->create(['name' => 'Jane Doe']);

    $this->get('/usuarios?order=name&direction=asc')
        ->assertSeeInOrder([
            'Jane Doe',
            'John Doe',
            'Richard Roe',
        ]);

    $this->get('/usuarios?order=name&direction=desc')
        ->assertSeeInOrder([
            'Richard Roe',
            'John Doe',
            'Jane Doe',
        ]);
  }

  /** @test */
  function users_are_ordered_by_email()
  {
    factory(User::class)->create(['email' => 'john.doe@example.com']);
    factory(User::class)->create(['email' => 'richard.roe@example.com']);
    factory(User::class)->create(['email' => 'jane.doe@example.com']);

    $this->get('/usuarios?order=email&direction=asc')
        ->assertSeeInOrder([
            'jane.doe@example.com',
            'john.doe@example.com',
            'richard.roe@example.com',
        ]);

    $this->get('/usuarios?order=email&direction=desc')
        ->assertSeeInOrder([
            'richard.roe@example.com',
            'john.doe@example.com',
            'jane.doe@example.com',
        ]);
  }

  /** @test */
  function users_are_ordered_by_registration_date()
  {
    factory(User::class)->create(['name' => 'John Doe', 'created_at' => now()->subDays(2)]);
    factory(User::class)->create(['name' => 'Jane Doe', 'created_at' => now()->subDays(5)]);
    factory(User::class)->create(['name' => 'Richard Roe', 'created_at' => now()->subDays(3)]);

    $this->get('/usuarios?order=created_at&direction=asc')
        ->assertSeeInOrder([
            'Jane Doe',
            'Richard Roe',
            'John Doe',
        ]);

    $this->get('/usuarios?order=created_at&direction=desc')
        ->assertSeeInOrder([
            'John Doe',
            'Richard Roe',
            'Jane Doe',
        ]);
  }

  /** @test */
  function invalid_order_query_data_is_ignored_and_the_default_order_is_used_instead()
  {
    factory(User::class)->create(['name' => 'John Doe', 'created_at' => now()->subDays(2)]);
    factory(User::class)->create(['name' => 'Jane Doe', 'created_at' => now()->subDays(5)]);
    factory(User::class)->create(['name' => 'Richard Roe', 'created_at' => now()->subDays(3)]);

    $this->get('/usuarios?order=id&direction=asc')
        ->assertSeeInOrder([
            'John Doe',
            'Richard Roe',
            'Jane Doe',
        ]);

    $this->get('/usuarios?order=invalid_column&direction=desc')
        ->assertOk()
        ->assertSeeInOrder([
            'John Doe',
            'Richard Roe',
            'Jane Doe',
        ]);
  }

  /** @test */
  function invalid_direction_query_data_is_ignored_and_the_default_direction_is_used_instead()
  {
    factory(User::class)->create(['name' => 'John Doe']);
    factory(User::class)->create(['name' => 'Jane Doe']);
    factory(User::class)->create(['name' => 'Richard Roe']);

    $this->get('/usuarios?order=name&direction=down')
        ->assertOk()
        ->assertSeeInOrder([
            'Jane Doe',
            'John Doe',
            'Richard Roe',
        ]);
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
        // ->assertSee(trans('Listado de usuarios en papelera'))
        ->assertSee(trans('users.title.trash'))
        ->assertSee('Jon')
        ->assertDontSee('Jane');
  }
}