<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
  static $password;

  return [
      'name'     => $faker->name,
      'email'    => $faker->unique()->safeEmail,
      'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
      // 'password' => $password ?: $password = bcrypt('secret'),
      'remember_token' => Str::random(10),
      'role'  => $faker->randomElement(['user', 'admin']),
  ];
});

// Después que se cree el Usuario, se ejecute la función anónima
// Para crear un Perfil
$factory->afterCreating(User::class, function ($user, $faker) {
  // Obtener el objeto con el Perfil, luego guardarlo asociado
  // al Usuario a través de la asociación profile()
  $user->profile()->save(factory(UserProfile::class)->make());
});