<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{

  protected $fillable = ['map_id', 'name'];
  
  protected $table = 'slips';

  /* Each SLip belongs to only one Map */

  public function map()
  {
    return $this->belongsTo('App\Map', 'map_id');
  }

  /* Each Slip can have many Houses */
  public function houses()
  {
    return $this->hasMany('App\House');
  }

  public function streets()
  {
    return $this->hasManyThrough('App\Street', 'App\House');
  }
}
