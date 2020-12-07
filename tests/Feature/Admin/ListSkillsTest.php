<?php

namespace Tests\Feature\Admin;

use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListSkillsTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_shows_the_skills_list()
  {
    Skill::factory()->create(['name' => 'HTML']);

    Skill::factory()->create(['name' => 'PHP']);

    Skill::factory()->create(['name' => 'CSS']);

    $this->get('/habilidades')
        ->assertStatus(200)
        ->assertSeeInOrder([
          'CSS',
          'HTML',
          'PHP'
        ]);
  }
}
