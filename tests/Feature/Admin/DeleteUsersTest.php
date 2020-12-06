<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUsersTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_sends_a_user_to_trash()
  {
    $user = factory(User::class)->create();

    $user->skills()->attach(factory(Skill::class)->create());

    $this->patch("usuarios/{$user->id}/papelera")
        ->assertRedirect('usuarios');

    // Opción 1
    $this->assertSoftDeleted('users', [
      'id' => $user->id,
    ]);

    $this->assertSoftDeleted('user_skill', [
      'id' => $user->id,
    ]);

    $this->assertSoftDeleted('user_profiles', [
      'user_id' => $user->id,
    ]);

    // Opción 2
    $user->refresh();

    $this->assertTrue($user->trashed());
  }

  /** @test */
  public function it_completely_deletes_a_user()
  {
    $user = factory(User::class)->create([
      'deleted_at' => now()
    ]);

    $this->delete("usuarios/{$user->id}")
        // ->assertRedirect('usuarios/papelera');
        ->assertRedirect('usuarios');

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function it_cannot_delete_a_user_that_is_not_in_the_trash()
  {
    $this->withExceptionHandling();

    $user = factory(User::class)->create([
      'deleted_at' => null,
    ]);

    $this->delete("usuarios/{$user->id}")
      ->assertStatus(404);

    $this->assertDatabaseHas('users', [
      'id' => $user->id,
      'deleted_at' => null,
    ]);
  }
}
