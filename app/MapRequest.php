<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MapRequest extends Model
{
    /* whitelist variables for mass-assignment, all others will be blacklisted */
    protected $fillable = ['user_id', 'map_id', 'requested_on', 'responded_on', 'status', 'message'];

    /**
     * Additional fields to treat as carbon instances
     * @var array
     */
      protected $dates = ['requested_on', 'responded_on'];

      /**
      * By parsing the "published_at" date field, we will be adding a default time of 00:00:00
      *
      * @param [type] $date [description]
      */
      public function setAssignedOnAttribute($date)
      {
         $this->attributes['requested_on'] = Carbon::parse($date);
      }

      /**
      * By parsing the "published_at" date field, we will be adding a default time of 00:00:00
      *
      * @param [type] $date [description]
      */
      public function setFinishedOnAttribute($date)
      {
         $this->attributes['responded_on'] = Carbon::parse($date);
      }



    /**
     * Scope queries to assignments that haven't been finished
     *
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
      public function scopeUnfinished($query)
      {
        $query->where('responded_on', NULL);
      }

    /**
     * Scope queries to assignments that have been finished
     *
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
        public function scopeFinished($query)
        {
          $query->whereNotNull('responded_on');
        }

     /**
     * Only one map belongs to a map Request 
     * @return [type] [description]
     */
          public function map()
          {
            return $this->belongsTo('App\Map', 'map_id');
          }

    /**
     * Only one user belongs to a map request
     * @return [type] [description]
     */
        public function user()
        {
          return $this->belongsTo('App\User', 'user_id');
        }
}
