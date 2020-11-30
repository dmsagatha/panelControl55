<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserQuery extends Builder
{
  public function findByEmail($email)
  {
    return $this->where(compact('email'))->first();
  }
}