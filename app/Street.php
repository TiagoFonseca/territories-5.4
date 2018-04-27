<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
  protected $fillable = ['name'];


  /* Each Street can have many Houses */
  public function houses()
  {
    return $this->hasMany('App\House');
  }
}
