<?php

use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
  static $password;

  return [
      'first_name' => $faker->firstName,
      'last_name'  => $faker->lastName,
      'email' => $faker->unique()->safeEmail,
      'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
      // 'password' => $password ?: $password = bcrypt('secret'),
      'remember_token' => Str::random(10),
      'role'  => 'user',
  ];
});