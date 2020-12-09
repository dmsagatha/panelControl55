<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchUsersTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function search_users_by_name()
  {
    $john = User::factory()->create([
      'name' => 'John',
      'email' => 'john@example.com',
    ]);

    $jane = User::factory()->create([
      'name' => 'Jane',
      'email' => 'jane@example.com',
    ]);

    $this->get('/usuarios?search=John')
        ->assertStatus(200)
        ->assertViewHas('users', function ($users) use ($john, $jane) {
          return $users->contains($john) && !$users->contains($jane);
        });
  }

  /** @test */
  public function partial_search_by_name()
  {
    $john = User::factory()->create([
      'name' => 'John'
    ]);

    $jane = User::factory()->create([
      'name' => 'Jane',
    ]);

    $this->get('/usuarios?search=Jo')
        ->assertStatus(200)
        ->assertViewHas('users', function ($users) use ($john, $jane) {
          return $users->contains($john) && !$users->contains($jane);
        });
  }

  /** @test */
  public function search_users_by_email()
  {
    $john = User::factory()->create([
      'email' => 'john@example.com',
    ]);

    $jane = User::factory()->create([
      'email' => 'jane@example.net',
    ]);

    $this->get('/usuarios?search=john@example.com')
        ->assertStatus(200)
        ->assertViewHas('users', function ($users) use ($john, $jane) {
          return $users->contains($john) && !$users->contains($jane);
        });
  }

  /** @test */
  public function show_results_with_a_partial_search_by_email()
  {
    $john = User::factory()->create([
      'email' => 'john@example.com',
    ]);

    $jane = User::factory()->create([
      'email' => 'jane@example.net',
    ]);

    $this->get('/usuarios?search=john@example')
        ->assertStatus(200)
        ->assertViewHas('users', function ($users) use ($john, $jane) {
          return $users->contains($john) && !$users->contains($jane);
        });
  }

  /** @test */
  public function search_users_by_team_name()
  {
    $john = User::factory()->create([
      'name' => 'John',
      'team_id' => Team::factory()->create(['name' => 'Smuggler'])->id,
    ]);

    $jane = User::factory()->create([
      'name' => 'Jane',
      'team_id' => null,
    ]);

    $marlene = User::factory()->create([
      'name' => 'Marlene',
      'team_id' => Team::factory()->create(['name' => 'Firefly'])->id,
    ]);

    $response = $this->get('/usuarios?search=Firefly')
        ->assertStatus(200)
        ->assertViewHas('users', function ($users) use ($marlene, $john, $jane) {
          return $users->contains($marlene)
                && !$users->contains($john)
                && !$users->contains($jane);
        });

    $response->assertViewCollection('users')
        ->contains($marlene)
        ->notContains($john)
        ->notContains($jane);
  }

  /** @test */
  public function partial_search_by_team_name()
  {
    $john = User::factory()->create([
      'name' => 'John',
      'team_id' => Team::factory()->create(['name' => 'Smuggler'])->id,
    ]);

    $jane = User::factory()->create([
      'name' => 'Jane',
      'team_id' => null,
    ]);

    $marlene = User::factory()->create([
      'name' => 'Marlene',
      'team_id' => Team::factory()->create(['name' => 'Firefly'])->id,
    ]);

    $response = $this->get('/usuarios?search=Fire')
        ->assertStatus(200);

    $response->assertViewCollection('users')
        ->contains($marlene)
        ->notContains($john)
        ->notContains($jane);
  }
}
