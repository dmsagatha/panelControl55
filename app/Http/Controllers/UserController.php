<?php

namespace App\Http\Controllers;

use App\Models\{User, UserProfile, Profession, Skill};
use App\Http\Requests\{UserCreateRequest, UserUpdateRequest};
use App\Http\Forms\UserForm;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index(Request $request)
  {    
    // 2-26 Búsqueda avanzada con Eloquent usando whereHas y Scopes
    // scopeSearch en Userp.php
    // 2-33 Filtrar datos por campos de tipo radio
    // 2-35 Filtrar datos por campos de select - UserQuery - Trait
    $users = User::query()
        ->with('team', 'skills', 'profile.profession')
        ->filterBy($request->only(['state', 'role', 'search']))
        ->orderByDesc('created_at')
        ->paginate()
        ->appends(request(['search']));

    return view('users.index', [
      'users'  => $users,
      'view'   => 'index',
      'skills' => Skill::orderBy('name')->get(),
      'checkedSkills' => collect(request('skills'))
    ]);
  }

  public function trashed()
  {
    //$users = User::onlyTrashed()->get();
    $users = User::with('team', 'skills', 'profile.profession')
        ->onlyTrashed()
        ->paginate();

    $title = 'Listado de usuarios en papelera';

    return view('users.index', [
      'users' => $users,
      'view'  => 'trash',
    ]);
  }
  
  public function show(User $user)
  {
    // /usuarios/1000 ==> 404  No encontrado
    return view('users.show', compact('user'));
  }

  public function create()
  {
    return $this->form('users.create', new User);
  }

  public function store(UserCreateRequest $request)
  {
    $request->createUser();
    
    return redirect()->route('users.index');
  }

  public function edit(User $user)
  {
    return $this->form('users.edit', $user);
  }

  protected function form($view, User $user)
  {
    return view($view, [
      'professions' => Profession::orderBy('title', 'ASC')->get(),
      'skills' => Skill::orderBy('name', 'ASC')->get(),
      'user'   => $user,
    ]);
  }

  /* public function update(User $user)
  {
    $data = request()->validate([
      'name'  => 'required',
      // 'email'    => 'required|email|unique:users,email,'.$user->id,
      'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
      'password' => '',
      'role' => '',
      'bio' => '',
      'twitter' => '',
      'profession_id' => '',
      'skills'  =>'',
    ]);

    if ($data['password'] != null) {
      $data['password'] = bcrypt($data['password']);
    } else {
      // Si es nula, eliminarla del array de los  datos
      unset($data['password']);
    }

    $user->fill($data);
    $user->role = $data['role'];
    $user->save();

    $user->profile->update($data);

    $user->skills()->sync($data['skills'] ?? []);   // Undefined index: skills

    return redirect()->route('users.show', ['user' => $user]);
  } */
  
  /**
   * 2-17-Uso de Form Requests para validar la actualización de registros
   */
  public function update(UserUpdateRequest $request, User $user)
  {
    $request->updateUser($user);
    
    return redirect()->route('users.show', ['user' => $user]);
  }

  /** Elimnar el Usuario de forma lógica */
  public function trash(User $user)
  {
    $user->delete();
    $user->profile()->delete();

    return redirect()->route('users.index');
  }
  
  public function destroy($id)
  {
    $user = User::onlyTrashed()->whereId($id)->firstOrFail();

    $user->forceDelete();
    
    return redirect()->route('users.index')->with('status', 'El usuario fue eliminado con éxito!');
    // return redirect()->route('users.trashed');
  }
}