<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
  public function index()
  {
    $professions = Profession::query()
        ->withCount('profiles')
        ->orderBy('title')
        ->get();

    return view('professions.index', [
        'professions' => $professions,
    ]);
  }

  public function destroy(Profession $profession)
  {
    abort_if($profession->profiles()->exists(), 400, 'No se puede eliminar una profesión vinculada a un perfil.');
    
    $profession->delete();

    return redirect('profesiones');
  }
}