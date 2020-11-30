<?php

namespace App\Models;

use App\Models\QueryFilter;
use Illuminate\Support\Facades\DB;

class UserFilter extends QueryFilter
{
  public function rules(): array
  {
    return [
      'search' => 'filled',
      'state'  => 'in:active,inactive',
      'role'   => 'in:admin,user',
      'skills' => 'array|exists:skills,id',
    ];
  }

  public function filterBySearch($query, $search)
  {
    return $query->where('name', 'like', "%{$search}%")
        ->orWhere('email', 'like', "%{$search}%")
        ->orWhereHas('team', function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%");
        });
  }

  public function filterByState($query, $state)
  {
    return $query->where('active', $state == 'active');
  }

  /**
   * Filtrar los usuarios de acuerdo a las habilidades seleccionadas
   */
  /* public function filterBySkillsTemporal($query, $skills)
  {
    $query->whereHas('skills', function ($q) use ($skills) {
      // Obtener las habilidades donde su id se encuentre en el
      // array de habilidades
      $q->whereIn('skills.id', $skills)
      // Obtener solamente donde la cantidad de habilidades sea
      // igual a la cantidad de habilidades que espera obtener
        ->havingRaw('COUNT(skills.id) = ?', [count($skills)]);
    });
  } */

  
  public function filterBySkills($query, $skills)
  {
    /*
     * SELECT * FROM `users` WHERE (
          SELECT COUNT(`s`.`id`)
            FROM `user_skill` AS `s`
            WHERE `s`.`user_id` = `users`.`id`
            AND `s`.`skill_id` IN (4,2)
      ) = 2
     */
    $subquery = DB::table('user_skill AS s')
        // Seleccionar la cuenta de registros
        ->selectRaw('COUNT(`s`.`id`)')
        // Comparar el valor de dos columnas
        // ->whereRaw('`s`.`user_id` = `users`.`id`')
        ->whereColumn('s.user_id', 'users.id')
        // Comprobar que el valor de la columna skill_id este
        // dentro del listado de habilidades esperado
        ->whereIn('skill_id', $skills);

    // Verificar que el resultado de la subconsulta sea igual
    // a la cantidad de habilidades esperado
    $query->whereQuery($subquery, count($skills));
  }
}