<?php

namespace Tests\Feature\Admin;

use App\Models\Profession;
use App\Models\User;
use App\Models\UserProfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteProfessionsTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_deletes_a_profession()
  {
    $profession = Profession::factory()->create();

    $response = $this->delete("profesiones/{$profession->id}");

    $response->assertRedirect();

    // Verifcar que la tabla de profesiones este vaía
    $this->assertDatabaseEmpty('professions');
  }

  /** @test */
  public function a_profession_associated_to_a_profile_cannot_be_deleted()
  {
    $this->withExceptionHandling();

    $profession = Profession::factory()->create();

    $user = User::factory()->create();
    $user->profile->update([
      'profession_id' => $profession->id,
    ]);

    $response = $this->delete("profesiones/{$profession->id}");

    $response->assertStatus(400);

    $this->assertDatabaseHas('professions', [
      'id' => $profession->id,
    ]);
  }
}
