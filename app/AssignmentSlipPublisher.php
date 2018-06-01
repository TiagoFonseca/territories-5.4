<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentSlipPublisher extends Model
{
      protected $table = 'assignment_slip_publisher';
      protected $fillable = ['name', 'date', 'period_of_day', 'last_house'];
      protected $dates = ['date'];

       

      
}
