<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable, SoftDeletes;
  
  /* protected $fillable = [
    'name', 'email', 'password'
  ]; */

  protected $guarded = [];        // Video 2-18

  /* public function getPerPage()
  {
      return parent::getPerPage() * 2;  // Video 2-21
  } */

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
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  public static function findByEmail($email)
  {
    return static::where(compact('email'))->first();
  }

  public function isAdmin()
  {
    // return $this->email === 'superadmin@admin.net';
    return $this->role === 'admin';
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
}