<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
  protected $fillable = ['slip_id', 'number', 'street_id', 'type', 'status', 'description'];
  
  protected $table = 'houses';

  /* Each House belongs to one Slip */
  public function slip()
  {
    return $this->belongsTo('App\Slip', 'slip_id');
  }

  public function street()
  {
    return $this->belongsTo('App\Street', 'street_id');
  }
  
  public function assignmenthouses()
    {
        return $this->hasMany('App\AssignmentHouse');
    }
  
}
