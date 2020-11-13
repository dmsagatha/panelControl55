<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function index()
  {
    //$users = DB::table('users')->get();
    $users = User::with('profession')->get();

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
    return view('users.create');
  }

  public function store()
  {
    /* $data = request()->all();

    if (empty($data['name'])) {
      return redirect('usuarios/nuevo')->withErrors([
        'name' => 'El campo nombre es obligatorio'
      ]);
    } */
    $data = request()->validate([
        'name'  => 'required',
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => 'required'
    ], [
        'name.required' => 'El campo nombre es obligatorio'
    ]);

    User::create([
      'name'     => $data['name'],
      'email'    => $data['email'],
      'password' => bcrypt($data['password']),
    ]);
    
    return redirect()->route('users.index');
  }

  public function edit(User $user)
  {
    return view('users.edit', ['user' => $user]);
  }

  public function update(User $user)
  {
    $data = request()->validate([
        'name'  => 'required',
        'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        'password' => 'required'
    ]);

    $data = request()->all();

    $data['password'] = bcrypt($data['password']);

    $user->update($data);

    return redirect()->route('users.show', ['user' => $user]);
  }
}