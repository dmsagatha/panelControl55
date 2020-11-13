<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

  public function show($id)
  {
    $user = User::find($id);

    return view('users.show', compact('user'));
  }

  public function create()
  {
    return 'Crear nuevo usuario';
  }
}