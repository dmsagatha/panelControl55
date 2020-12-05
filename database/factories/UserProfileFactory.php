<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserProfile;
use App\Models\Profession;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(UserProfile::class, function (Faker $faker) {
  $professions = Profession::pluck('id')->all();

  return [
    // 'user_id' => factory(User::class),
    'bio' => $faker->paragraph(),
    'profession_id' => $faker->randomElement($professions),
  ];
});
