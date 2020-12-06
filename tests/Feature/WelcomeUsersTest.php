<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
  /** @test */
  public function it_welcomes_users_with_nickname()
  {
    $this->get('saludo/doris/agatha')
        ->assertStatus(200)
        ->assertSee('Bienvenido Doris, tu apodo es agatha');
  }

  /** @test */
  public function it_welcomes_users_without_nickname()
  {
    $this->get('saludo/Doris')
        ->assertStatus(200)
        ->assertSee('Bienvenido Doris');
  }
}
