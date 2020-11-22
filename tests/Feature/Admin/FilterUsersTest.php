<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterUsersTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function filter_users_by_state_active()
  {
    $activeUser = factory(User::class)->create(['active' => true]);

    $inactiveUser = factory(User::class)->create(['active' => false]);

    $response = $this->get('/usuarios?state=active');

    $response->assertViewCollection('users')
        ->contains($activeUser)
        ->notContains($inactiveUser);
  }

  /** @test */
  function filter_users_by_state_inactive()
  {
    $activeUser = factory(User::class)->create(['active' => true]);
    $inactiveUser = factory(User::class)->create(['active' => false]);

    $response = $this->get('usuarios?state=inactive');

    $response->assertStatus(200);

    $response->assertViewCollection('users')
      ->contains($inactiveUser)
      ->notContains($activeUser);
  }
}