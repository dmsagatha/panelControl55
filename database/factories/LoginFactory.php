<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Login;
use Faker\Generator as Faker;

$factory->define(Login::class, function (Faker $faker) {
  return [
    'created_at' => $faker->dateTime('now', 'America/Bogota'),
  ];
});