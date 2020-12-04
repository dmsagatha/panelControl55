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
  function gets_the_last_login_datetime_of_each_user()
  {
    $john = factory(User::class)->create(['name' => 'John']);
    factory(Login::class)->create([
        'user_id' => $john->id,
        'created_at' => '2019-09-18 12:30:00',
    ]);
    factory(Login::class)->create([
        'user_id' => $john->id,
        'created_at' => '2019-09-18 12:31:00',
    ]);
    factory(Login::class)->create([
        'user_id' => $john->id,
        'created_at' => '2019-09-17 12:31:00',
    ]);

    $jane = factory(User::class)->create(['name' => 'Jane']);
    factory(Login::class)->create([
        'user_id' => $jane->id,
        'created_at' => '2019-09-15 12:00:00',
    ]);
    factory(Login::class)->create([
        'user_id' => $jane->id,
        'created_at' => '2019-09-15 12:01:00',
    ]);
    factory(Login::class)->create([
        'user_id' => $jane->id,
        'created_at' => '2019-09-15 11:59:59',
    ]);

    $users = User::all();

    /* $this->assertTrue(
      $users->firstWhere('name', 'John')->lastLogin->created_at->eq('2019-09-18 12:31:00')
    ); */

    $this->assertEquals(Carbon::parse('2019-09-18 12:31:00'), $users->firstWhere('name', 'John')->lastLogin->created_at);

    $this->assertEquals(Carbon::parse('2019-09-15 12:01:00'), $users->firstWhere('name', 'Jane')->lastLogin->created_at);
  }
}