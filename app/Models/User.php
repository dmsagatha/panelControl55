<?php

namespace App\Models;

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
    'name', 'email', 'password',
  ];

  public function profession()
  {
    return $this->belongsTo(Profession::class);
  }

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