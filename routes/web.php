<?php

Route::get('/', function () {
  return view('welcome');
});

Route::get('/usuarios', 'UserController@index')->name('users.index');

Route::get('/usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('users.show');

Route::get('/usuarios/nuevo', 'UserController@create')->name('users.create');
Route::post('/usuarios', 'UserController@store')->name('users.store');

Route::get('/usuarios/{user}/editar', 'UserController@edit')->name('users.edit');
Route::put('/usuarios/{user}', 'UserController@update')->name('users.update');

// Route::get('/usuarios/papelera', 'UserController@trashed')->name('users.trashed');
Route::get('/usuarios/papelera', 'UserController@index')->name('users.trashed');
Route::patch('/usuarios/{user}/papelera', 'UserController@trash')->name('users.trash');
//Route::delete('/usuarios/{user}', 'UserController@destroy')->name('users.destroy');
Route::delete('/usuarios/{id}', 'UserController@destroy')->name('users.destroy');

// Profile
Route::get('/editar-perfil/', 'ProfileController@edit')->name('profile.edit');
Route::put('/editar-perfil/', 'ProfileController@update')->name('profile.update');

// Profesiones
Route::get('/profesiones/', 'ProfessionController@index');
Route::delete('/profesiones/{profession}', 'ProfessionController@destroy')->name('professions.destroy');

// Skills
Route::get('/habilidades/', 'SkillController@index');

Route::get('/saludo/{name}/{nickname?}', 'WelcomeUserController');
