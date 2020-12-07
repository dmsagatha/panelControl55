<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Login;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function gets_the_last_login_datetime_of_each_user()
  {
    $john = User::factory()->create(['name' => 'John']);
    Login::factory()->create([
      'user_id' => $john->id,
      'created_at' => '2019-09-18 12:30:00',
    ]);
    Login::factory()->create([
      'user_id' => $john->id,
      'created_at' => '2019-09-18 12:31:00',
    ]);
    Login::factory()->create([
      'user_id' => $john->id,
      'created_at' => '2019-09-17 12:31:00',
    ]);

    $jane = User::factory()->create(['name' => 'Jane']);
    Login::factory()->create([
      'user_id' => $jane->id,
      'created_at' => '2019-09-15 12:00:00',
    ]);
    Login::factory()->create([
      'user_id' => $jane->id,
      'created_at' => '2019-09-15 12:01:00',
    ]);
    Login::factory()->create([
      'user_id' => $jane->id,
      'created_at' => '2019-09-15 11:59:59',
    ]);

    $users = User::withLastLogin()->get();

    $this->assertInstanceOf(Carbon::class, $users->firstWhere('name', 'John')->last_login_at);

    $this->assertEquals(Carbon::parse('2019-09-18 12:31:00'), $users->firstWhere('name', 'John')->last_login_at);
    $this->assertEquals(Carbon::parse('2019-09-15 12:01:00'), $users->firstWhere('name', 'Jane')->last_login_at);
  }
}
