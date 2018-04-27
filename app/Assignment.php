<?php

namespace App;
use Auth;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    /* whitelist variables for mass-assignment, all others will be blacklisted */
    protected $fillable = ['user_id', 'map_id', 'assigned_on', 'finished_on'];

    /**
     * Additional fields to treat as carbon instances
     * @var array
     */
      protected $dates = ['assigned_on', 'finished_on'];

      /**
      * By parsing the "published_at" date field, we will be adding a default time of 00:00:00
      *
      * @param [type] $date [description]
      */
      public function setAssignedOnAttribute($date)
      {
        $this->attributes['assigned_on'] = Carbon::parse($date);
      }

      /**
      * By parsing the "published_at" date field, we will be adding a default time of 00:00:00
      *
      * @param [type] $date [description]
      */
      public function setFinishedOnAttribute($date)
      {
         $this->attributes['finished_on'] = Carbon::parse($date);
      }



    /**
     * Scope queries to assignments that haven't been finished
     *
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
      public function scopeUnfinished($query)
      {
        $query->where('finished_on', NULL);
      }

    /**
     * Scope queries to assignments that have been finished
     *
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
        public function scopeFinished($query)
        {
          $query->whereNotNull('finished_on');
        }

     /**
     * An Assignment belongs to (it's more like only has, need to check this one later...) only one Map
     * @return [type] [description]
     */
          public function map()
          {
            return $this->belongsTo('App\Map', 'map_id');
          }

    /**
     * An Assignment belongs to only one User
     * @return [type] [description]
     */
        public function user()
        {
          return $this->belongsTo('App\User', 'user_id');
        }
        
         public function scopeMyAssignedMaps($query)
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
