<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentHouse extends Model
{
    /* whitelist variables for mass-assignment, all others will be blacklisted */
    protected $fillable = ['assignment_id', 'house_id', 'status', 'notes'];
    
    protected $table = 'assignments_houses';
    
    /**
     * An Assignment_House belongs to only one Assignment
     * @return [type] [description]
     */
          public function assignment()
          {
            return $this->belongsTo('App\Assignment', 'assignment_id');
          }

    /**
     * An Assignment_house belongs to only one house
     * @return [type] [description]
     */
        public function house()
        {
          return $this->belongsTo('App\House', 'house_id');
        }
}
