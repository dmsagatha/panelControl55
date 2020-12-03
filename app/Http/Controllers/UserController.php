<?php

namespace App\Http\Controllers;

use App\Models\{User, Profession, Skill, Sortable, UserFilter};
use App\Http\Requests\{UserCreateRequest, UserUpdateRequest};
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index(Request $request, UserFilter $filters, Sortable $sortable)
  {
    // 2-44 Orden dinámico de registros
    // 2-45 Validación para el orden dinámico
    // 2-46 Combinando la funcionalidad de filtros, orden dinámico y paginación
    $users = User::query()
        ->onlyTrashedIf($request->routeIs('users.trashed'))   //QueryBuilder
        ->with('team', 'skills', 'profile.profession')
        ->filterBy($filters, $request->only(['state', 'role', 'search', 'skills', 'from', 'to', 'order']))
        ->orderByDesc('created_at')
        ->paginate()
        ->appends($filters->valid());

    $sortable->appends($filters->valid());

    return view('users.index', [
      'view'   => $request->routeIs('users.trashed') ? 'trash' : 'index',
      'users'  => $users,
      'skills' => Skill::orderBy('name')->get(),
      'checkedSkills' => collect(request('skills')),
      'sortable' => $sortable,
    ]);
  }
  
  public function show(User $user)
  {
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
  }
}