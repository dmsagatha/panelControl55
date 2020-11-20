<?php

namespace App\Http\Controllers;

use App\Models\{User, UserProfile, Profession, Skill};
use App\Http\Forms\UserForm;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index()
  {
    //$users = DB::table('users')->get();
    //$users = User::all();
    // 2-21 Paginación
    // $users = User::orderByDesc('created_at')->paginate(15);
    $users = User::orderByDesc('created_at')->simplePaginate();

    $title = 'Listado de usuarios';

    /* return view('users.index')
        ->with('users', User::all())
        ->with('title', 'Listado de usuarios'); */

    return view('users.index', compact('users', 'title'));
  }

  public function trashed()
  {
    //$users = User::onlyTrashed()->get();
    $users = User::onlyTrashed()->simplePaginate();

    $title = 'Listado de usuarios en papelera';

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

    // return new UserForm('users.create', new User);  // App\Http\Forms/UserForm

    return $this->form('users.create', new User);
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

    // return new UserForm('users.edit', $user);  // App\Http\Forms/UserForm

    return $this->form('users.edit', $user);
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

  protected function form($view, User $user)
  {
    return view($view, [
      'professions' => Profession::orderBy('title', 'ASC')->get(),
      'skills' => Skill::orderBy('name', 'ASC')->get(),
      'roles'  => trans('users.roles'),
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