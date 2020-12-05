<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    protected $fillable = ['title'];

    /* public function getRouteKeyName()
    {
      return 'title';
    } */

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }
}
