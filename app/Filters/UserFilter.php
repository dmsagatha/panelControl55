<?php

namespace App\Filters;

use App\Sortable;
use App\Rules\SortableColumn;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Filters\QueryFilter;

class UserFilter extends QueryFilter
{
  protected $aliases = [
    'date' => 'created_at',
    'login' => 'last_login_at',
  ];

  public function rules(): array
  {
    return [
      'search' => 'filled',
      'state' => 'in:active,inactive',
      'role' => 'in:admin,user',
      'skills' => 'array|exists:skills,id',
      'from' => 'date_format:d/m/Y',
      'to' => 'date_format:d/m/Y',
      'order' => [new SortableColumn(['name', 'email', 'date', 'login'])],
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

  public function order($query, $value)
  {
    [$column, $direction] = Sortable::info($value);

    $query->orderBy($this->getColumnName($column), $direction);
  }

  protected function getColumnName($alias)
  {
    return $this->aliases[$alias] ?? $alias;
  }
}
