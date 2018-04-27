<?php

namespace App;

use Auth;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{

  protected $fillable = ['name', 'number', 'area', 'picture', 'tags'];
  
  protected $table = 'maps';

  /**
  * Additional fields to treat as carbon instances
  * @var array
  */
  protected $dates = ['published_at'];



/**
 * Each Map has many assignments (??) */

  public function users(){
    return $this->belongsToMany('App\User', 'assignments', 'map_id', 'user_id');
  }
  
  /**
 * Each Map has many assignments (??) */

  // public function assignments(){
  //   return $this->hasMany('App\Assignment');
  // }
          
/**
 * Each Map has many Slips
 */
  public function slips()
  {
    return $this->hasMany('App\Slip');
  }
  
   public function assignments()
  {
    return $this->hasMany('App\Assignment');
  }

  public function houses()
  {
      return $this->hasManyThrough('App\House', 'App\Slip');
  }
  
   public function maprequests()
    {
        return $this->hasMany('App\MapRequests');
    }
  
    /**
  * Scope queries to territories that have been assigned
  *
  * @param  [type] $query [description]
  * @return [type]        [description]
  */
  public function scopeUnavailable($query)
  {
    //$query->whereNotNull('finished_on');
    $query->whereHas('users', function ($q) {
        $q->where('finished_on', NULL);
    });
  }
  
     /**
  * Scope queries to territories that are being worked on by the logged in user
  *
  * @param  [type] $query [description]
  * @return [type]        [description]
  */
  public function scopeMyMaps($query)
  {
    $user = Auth::user();
    //dd($user);
    //$query->whereNotNull('finished_on');
    $query->whereHas('users', function ($q) use($user){
        $q->where('finished_on', NULL)
          ->where('user_id', $user->id);
    });
  }
  
  
}
