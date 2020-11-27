<?php

namespace App\Http\Requests;

use App\Models\{User, Role};
use Illuminate\Validation\Rule;
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
      // 'role'     => 'in:admin,user',  //Que el rol este dentro de los valores
      // 'role'     => 'nullable|in:'.implode(',', Role::getList()),  //Role.php
      'role'     => ['nullable', Rule::in(Role::getList())],  //Role.php
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
      'state' => [
        Rule::in(['active', 'inactive']),
      ]
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

    /* DB::transaction(function() {
      $data = $this->validated();
      
      $user = new User([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => bcrypt($data['password']),
      ]);

      // No adicionarlo en fillable()
      $user->role = $data['role'] ?? 'user';

      $user->save();

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
    }); */

    // 2-18-Asignación masiva en Eloquent ORM a fondo (uso de fillable)
    DB::transaction(function () {
      $user = User::create([
          'name'  => $this->name,
          'email' => $this->email,
          'password' => bcrypt($this->password),
          'role' => $this->role ?? 'user',
          // 'active' => $this->state == 'active',
          'state'  => $this->state,  // 2-34 Usar campos y atributos diferentes
      ]);

      $user->save();

      $user->profile()->create([
          'bio' => $this->bio,
          'twitter' => $this->twitter,    // 'present'
          'profession_id' => $this->profession_id,    // 'present'
      ]);

      if ($this->skills != null) {
          $user->skills()->attach($this->skills);   // Para usuario nuevo
      }
    });
  }
}