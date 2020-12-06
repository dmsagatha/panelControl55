<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
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

  /**
   * @return UserQuery
   */
  public static function query()
  {
    return parent::query(); // TODO: Change the autogenerated stub
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
    'last_login_at' => 'datetime',
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

  public function delete()
  {
    DB::transaction(function () {
      if (parent::delete()) {
        $this->profile()->delete();

        DB::table('user_skill')
            ->where('user_id', $this->id)
            ->update(['deleted_at' => now()]);
      }
    });
  }
}
