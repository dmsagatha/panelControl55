<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }
  
  public function rules()
  {
    return [
      'name'     => 'required',
      'email'    => ['required', 'email', 'unique:users,email'],
      'password' => 'required',
      'bio'      => 'required',
      'twitter'  => ['nullable', 'url'],
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'El campo nombre es obligatorio'
    ];
  }

  public function createUser()
  {
    // validated() - Devuelve un array con los datos validados
    // User::createUser($this->validated());

    DB::transaction(function() {
      $data = $this->validated();
      
      $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => bcrypt($data['password']),
      ]);

      $user->profile()->create([
        'bio'     => $data['bio'],
        //'twitter' => isset($data['twitter']) ? $data['twitter'] : null,
        //'twitter' => $this->twitter,
        //'twitter' => array_get($data, 'twitter'),
        'twitter' => $data['twitter'] ?? null,
      ]);
    });
  }
}