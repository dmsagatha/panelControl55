<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password'
  ];

  public function profile()
  {
    return $this->hasOne(UserProfile::class);
  }

  public function skills()
  {
    return$this->belongsToMany(Skill::class, 'user_skill');
  }

  // Crear con transaction, que los datos no se persistan en la bd
  /* public static function createUser($data)
  {
    DB::transaction(function() use ($data) {
      $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => bcrypt($data['password']),
      ]);
      $user->profile()->create([
        'bio'     => $data['bio'],
        'twitter' => $data['twitter'],
      ]);
    });
  } */

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * Convertir de integer a boolean
   */
  protected $casts = [
    'is_admin' => 'boolean'
  ];


  public static function findByEmail($email)
  {
    return static::where(compact('email'))->first();
  }

  public function isAdmin()
  {
    // return $this->email === 'superadmin@admin.net';
    return $this->is_admin;
  }
}