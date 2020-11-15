<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
      'twitter'  => ['nullable', 'present', 'url'],
      //'profession_id' => 'exists:professions,id',
      // Regla: La profesión este presente en el campo id de la tabla
      // professions y además, que solamente las profesiones donde
      // el campo selectable este como verdadero
      // 'profession_id' => Rule::exists('professions', 'id')->where('selectable', true),

      // El campo profession_id puede ser nulo, pero estar presente
      // así contenga una cadena vacía
      // Regla: La profesión este presente en el campo id de la tabla
      // professions y además, que solamente las profesiones donde 
      // el campo deleted_at sea Null, pueden ser seleccionadas
      'profession_id' => [
        'nullable', 'present',
        Rule::exists('professions', 'id')->whereNull('deleted_at')
      ],
      'skills'   => [
        'array',
        Rule::exists('skills', 'id'),
      ],
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
        'bio' => $data['bio'],
        //'twitter' => isset($data['twitter']) ? $data['twitter'] : null,
        //'twitter' => $this->twitter,
        //'twitter' => array_get($data, 'twitter'),
        // 'twitter' => $data['twitter'] ?? null,
        'twitter' => $data['twitter'],    // 'present'
        // 'profession_id' => $data['profession_id'] ?? null,
        'profession_id' => $data['profession_id'],    // 'present'
      ]);

      //$user->skills()->attach($data['skills'] ?? []);
      if (! empty($data['skills'])) {
        $user->skills()->attach($data['skills']);   // Para usuario nuevo
      }
    });
  }
}