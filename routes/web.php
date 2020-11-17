<?php

Route::get('/', function () {
  return view('welcome');
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

Route::get('/usuarios/{user}/editar', 'UserController@edit')->name('users.edit');
Route::put('/usuarios/{user}', 'UserController@update')->name('users.update');

Route::delete('/usuarios/{user}', 'UserController@destroy')->name('users.destroy');

// Profile
Route::get('/editar-perfil/', 'ProfileController@edit')->name('profile.edit');
Route::put('/editar-perfil/', 'ProfileController@update')->name('profile.update');

// Profesiones
Route::get('/profesiones/', 'ProfessionController@index');
Route::delete('/profesiones/{profession}', 'ProfessionController@destroy')->name('professions.destroy');

// Habilidades
Route::get('/habilidades/', 'SkillController@index');

Route::get('/saludo/{name}/{nickname?}', 'WelcomeUserController');