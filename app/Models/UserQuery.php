<?php

namespace App\Models;

use App\Models\FiltersQueries;
use Illuminate\Database\Eloquent\Builder;

class UserQuery extends Builder
{
  use FiltersQueries;
  
  public function findByEmail($email)
  {
    return static::where(compact('email'))->first();
  }

  protected function filterRules(): array
  {
    return [
        'search' => 'filled',
        'state'  => 'in:active,inactive',
        'role'   => 'in:admin,user',
    ];
  }

  public function filterBySearch($search)
  {
    return $this->where('name', 'like', "%{$search}%")
        ->orWhere('email', 'like', "%{$search}%")
        ->orWhereHas('team', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        });
  }

  public function filterByState($state)
  {
    return $this->where('active', $state == 'active');
  }

  /**
   * Buscar si el rol dado es admin o user
   */
  /* public function filterByRole($role)
  {
    if (in_array($role, ['user', 'admin'])) {
        return $this->where('role', $role);
    }

    return $this;
  } */
}