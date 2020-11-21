<?php

namespace App\Http\Controllers;

use App\Models\{User, Profession};
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function edit()
  {
    $user = User::first(); //or auth()->user()

    return view('profile.edit', [
        'user' => $user,
        'professions' => Profession::orderBy('title')->get(),
    ]);
  }

  public function update(Request $request)
  {
    $user = User::first(); //or auth()->user()

    $data = $request->all();  //FIXME: add validation

    /* if (empty($data['password'])) {
      unset($data['password']);
    } else {
      $data['password'] = bcrypt($data['password']);
    } */

    /* unset($data['password');
    $user->update($data);
    $user->profile->update($data); */

    $user->update([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email' => $request->email,
    ]);

    $user->profile->update([
        'bio' => $request->bio,
        'twitter' => $request->twitter,
        'profession_id' => $request->profession_id,
    ]);

    return redirect()->route('users.index');
  }
}