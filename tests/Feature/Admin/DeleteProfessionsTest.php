<?php

namespace Tests\Feature\Admin;

use App\Models\{Profession, User, UserProfile};
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteProfessionsTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function it_deletes_a_profession()
  {
    $profession = factory(Profession::class)->create();

    $response = $this->delete("profesiones/{$profession->id}");

    $response->assertRedirect();

    // Verifcar que la tabla de profesiones este vaía
    $this->assertDatabaseEmpty('professions');
  }

  /** @test */
  function a_profession_associated_to_a_profile_cannot_be_deleted()
  {
    $this->withExceptionHandling();

    $profession = factory(Profession::class)->create();

    $profile = factory(UserProfile::class)->create([
      'profession_id' => $profession->id,
      'user_id' => factory(User::class)->create()->id,
    ]);

    /* $user = factory(User::class)->create();
    $user->profile->update([
        'profession_id' => $profession->id,
    ]); */

    $response = $this->delete("profesiones/{$profession->id}");

    $response->assertStatus(400);

    $this->assertDatabaseHas('professions', [
        'id' => $profession->id,
    ]);
  }
}