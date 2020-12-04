<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable, SoftDeletes;

  protected $guarded = [];        // Video 2-18

  public function profile()
  {
    return $this->hasOne(UserProfile::class)->withDefault();
  }

  public function skills()
  {
    return$this->belongsToMany(Skill::class, 'user_skill');
  }

  public function team()
  {
    return$this->belongsTo(Team::class)->withDefault();
  }

  public function lastLogin()
  {
    return $this->hasOne(Login::class)->orderByDesc('created_at');
  }

  /**
   * Create a new Eloquent query builder for the model.
   *
   * @param  \Illuminate\Database\Query\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder|static
   */
  public function newEloquentBuilder($query)
  {
    return new UserQuery($query);
  }

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $casts = [
    'active' => 'bool',
  ];

  public function isAdmin()
  {
    return $this->role === 'admin';
  }

  public function setStateAttribute($value)
  {
    $this->attributes['active'] = $value == 'active';
  }

  public function getStateAttribute()
  {
    if ($this->active !== null) {
      return $this->active ? 'active' : 'inactive';
    }
  }
}