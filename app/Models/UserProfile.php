<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
  use SoftDeletes, HasFactory;

  protected $table = 'user_profiles';

  // protected $fillable = ['bio', 'twitter', 'profession_id'];

  protected $guarded = [];        // Video 2-18

  public function profession()
  {
    return $this->belongsTo(Profession::class)->withDefault([
      'title' => '(Sin profesión)'
    ]);  // _row
  }
}
