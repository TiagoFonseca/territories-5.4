<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AssignmentSlipPublisher;
use Carbon\Carbon;

class AssignmentSlipRecord extends Controller
{
    public function store(Request $request){
       $rules = [
         'date' => 'required',
         'period_of_day' => 'required',
         'last_house' => 'required'

     ];


      $this->validate($request, $rules);

      $date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateString();
      //$date = Carbon::parse($temp_date);
      //dd($temp_date);
      $AssignmentSlipPublisher = new AssignmentSlipPublisher;
      $AssignmentSlipPublisher->date = $date;
      $AssignmentSlipPublisher->period_of_day = $request->period_of_day;
      $AssignmentSlipPublisher->last_house = $request->last_house;
      $AssignmentSlipPublisher->assignment_slip_id = $request->assignment_slip_id;

      $AssignmentSlipPublisher->save();

      return back()->with("status", "Your record has been added, thank you.");
    }

    public function view(Request $request){

      $records = AssignmentSlipPublisher::where('assignment_slip_id', $request->id)->get();
      $response = [ 'data' => $records ];

      return response()->json($response);

    }

}
