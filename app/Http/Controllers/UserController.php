<?php

namespace App\Http\Controllers;

use App\Models\{User, UserProfile, Profession, Skill};
use App\Http\Forms\UserForm;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index()
  {
    //$users = DB::table('users')->get();
    $users = User::all();

    $title = 'Listado de usuarios';

    /* return view('users.index')
        ->with('users', User::all())
        ->with('title', 'Listado de usuarios'); */

    return view('users.index', compact('users', 'title'));
  }

  /* public function show($id)
  {
    // /usuarios/1000 ==> 404  No encontrado
    $user = User::findOrFail($id);
    //dd($user);

    return view('users.show', compact('user'));
  } */
  
  public function show(User $user)
  {
    // /usuarios/1000 ==> 404  No encontrado
    return view('users.show', compact('user'));
  }

  public function create()
  {
    // $user = new User;
    
    /* $professions = Profession::orderBy('title')->get();
    $skills      = Skill::orderBy('name')->get();
    $roles       = trans('users.roles');
    
    return view('users.create', compact('user', 'professions', 'skills', 'roles')); */

    // 2-13 - Compartir datos entre vistas de Laravel con View Composers
    // return view('users.create', compact('user'));

    // 2-15-Compartir datos entre vistas con métodos helpers
    /* $user = new User;
    return view('users.create', compact('user'))->with($this->formsData()); */

    return new UserForm('users.create', new User);  // App\Http\Forms/UserForm
  }

  public function store(UserCreateRequest $request)
  {
    /* $data = request()->all();

    if (empty($data['name'])) {
      return redirect('usuarios/nuevo')->withErrors([
        'name' => 'El campo nombre es obligatorio'
      ]);
    } */
    /* $data = request()->validate([
        'name'  => 'required',
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => 'required',
        'bio'      => 'required',
        'twitter'  => ''
    ], [
        'name.required' => 'El campo nombre es obligatorio'
    ]); */

    /* $user = User::create([
      'name'     => $data['name'],
      'email'    => $data['email'],
      'password' => bcrypt($data['password']),
    ]);

    $user->profile()->create([
      'bio'     => $data['bio'],
      'twitter' => $data['twitter'],
    ]); */
    
    // Crear con transaction, que los datos no se persistan en la bd
    /* DB::transaction(function() use ($data) {
      $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => bcrypt($data['password']),
      ]);

      $user->profile()->create([
        'bio'     => $data['bio'],
        'twitter' => $data['twitter'],
      ]);
    }); */

    // User.php
    // User::createUser($data);

    // UserCreateRequest.php
    $request->createUser();
    
    return redirect()->route('users.index');
  }

  public function edit(User $user)
  {
    /* $professions = Profession::orderBy('title')->get();
    $skills      = Skill::orderBy('name')->get();
    $roles       = trans('users.roles');
    
    return view('users.edit', compact('user', 'professions', 'skills', 'roles')); */

    // 2-13 - Compartir datos entre vistas de Laravel con View Composers
    // return view('users.edit', compact('user'));

    // 2-15-Compartir datos entre vistas con métodos helpers
    // return view('users.edit', compact('user'))->with($this->formsData());

    return new UserForm('users.edit', $user);  // App\Http\Forms/UserForm
  }

  /**
   * Método helper - No esta asociado a una ruta
   * 2-15-Compartir datos entre vistas con métodos helpers
   * Cambia a App\Http\Forms/UserForm.php
   */
  /* protected function formsData()
  {
    return [
      'professions' => Profession::orderBy('title')->get(),
      'skills'      => Skill::orderBy('name')->get(),
      'roles'       => trans('users.roles'),
    ];
  } */

  public function update(User $user)
  {
    $data = request()->validate([
      'name'  => 'required',
      // 'email'    => 'required|email|unique:users,email,'.$user->id,
      'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
      'password' => ''
    ]);

    if ($data['password'] != null) {
      $data['password'] = bcrypt($data['password']);
    } else {
      // Si es nula, eliminarla del array de los  datos
      unset($data['password']);
    }

    $user->update($data);

    return redirect()->route('users.show', ['user' => $user]);
  }
  
  public function destroy(User $user)
  {
    $user->delete();
    
    return redirect()->route('users.index');
  }
}