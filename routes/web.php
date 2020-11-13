<?php

Route::get('/', function () {
  return 'Home';
});

/* Route::get('/usuarios', function () {
   return 'Usuarios';
}); */

/* Route::get('/usuarios/{id}', function ($id) {
   return "Mostrando detalle del usuario: {$id}";
})->where('id', '[0-9]+'); */

/* Route::get('/usuarios/nuevo', function () {
   return 'Crear nuevo usuario';
}); */

/* Route::get('/saludo/{name}/{nickname?}', function ($name, $nickname = null) {
   $name = ucfirst($name);

   if ($nickname) {
       return "Bienvenido {$name}, tu apodo es {$nickname}";
   } else {
       return "Bienvenido {$name}";
   }
}); */

Route::get('/usuarios', 'UserController@index')->name('users.index');

Route::get('/usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('users.show');

Route::get('/usuarios/nuevo', 'UserController@create')->name('users.create');

Route::post('/usuarios', 'UserController@store')->name('users.store');

Route::get('/saludo/{name}/{nickname?}', 'WelcomeUserController');