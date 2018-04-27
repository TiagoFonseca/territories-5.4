<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentSlip extends Model
{
    /* whitelist variables for mass-assignment, all others will be blacklisted */
    protected $fillable = ['assignment_id', 'slip_id', 'shared'];
    
    protected $table = 'assignments_slips';
    
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
        public function slip()
        {
          return $this->belongsTo('App\Slip', 'slip_id');
        }
}
