<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUsersTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function it_deletes_a_user()
  {
    $user = factory(User::class)->create();

    $this->delete("usuarios/{$user->id}")
        ->assertRedirect('usuarios');

    /* $this->assertDatabaseMissing('users', [
        'id' => $user->id
    ]); */
    $this->assertDatabaseEmpty('users');
  }
}