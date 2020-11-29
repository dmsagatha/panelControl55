<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserQuery extends Builder
{
  public function findByEmail($email)
  {
    return static::where(compact('email'))->first();
  }

  public function search($search)
  {
    // Si la búsqueda esta vacía no se ejecute el método
    if (empty ($search)) {
      return $this;
    }

    return $this->where('name', 'like', "%{$search}%")
      ->orWhere('email', 'like', "%{$search}%")
      ->orWhereHas('team', function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%");
      });
  }

  public function byState($state)
  {
    if ($state == 'active') {
      return $this->where('active', true);
    }

    if ($state == 'inactive') {
      return $this->where('active', false);
    }

    return $this;
  }

  /**
   * Buscar si el rol dado es admin o user
   */
  public function byRole($role)
  {
    if (in_array($role, ['user', 'admin'])) {
        return $this->where('role', $role);
    }

    return $this;
  }
}