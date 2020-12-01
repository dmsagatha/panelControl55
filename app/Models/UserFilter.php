<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\QueryFilter;

class UserFilter extends QueryFilter
{
  public function rules(): array
  {
    return [
      'search' => 'filled',
      'state'  => 'in:active,inactive',
      'role'   => 'in:admin,user',
      'skills' => 'array|exists:skills,id',
      'from'   => 'date_format:d/m/Y',
      'to'     => 'date_format:d/m/Y',
    ];
  }

  public function search($query, $search)
  {
    return $query->where(function ($query) use ($search) {
        $query->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhereHas('team', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
          });
    });
  }

  public function state($query, $state)
  {
    return $query->where('active', $state == 'active');
  }
  
  public function skills($query, $skills)
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

  public function from($query, $date)
  {
    $date = Carbon::createFromFormat('d/m/Y', $date);

    $query->whereDate('created_at', '>=', $date);
  }

  public function to($query, $date)
  {
    $date = Carbon::createFromFormat('d/m/Y', $date);

    $query->whereDate('created_at', '<=', $date);
  }
}