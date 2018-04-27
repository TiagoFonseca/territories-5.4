<?php

namespace App\Http\Controllers\Frontend;

use App\Assignment;
use App\User;
use App\Map;
use App\Street;
use Input;
use App\Slip;
use App\House;
use App\AssignmentHouse;
use App\AssignmentSlip;
use App\Territory;
use Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Requests\AssignmentRequest;
use App\Http\Requests\AssignmentHouseRequest;

use Request;


class FrontendAssignmentsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      //
      $assignments = Assignment::all();


      return view('frontend.assignments.index', compact('assignments'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {

      $users = \DB::table('users')->lists('name', 'id');
      $maps = \DB::table('maps')->lists('name', 'id');

      return view('assignments.create', compact('users', 'maps'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(AssignmentRequest $request)
  {
       //only continues below if validation doesn't fail

      //dd($request->input('map_id'));
      Assignment::create($request->all());

      return redirect('assignments')->with('message', 'The assignment has been created!');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Assignment $request, $id)
  {
      $assignment = $request->find($id);

      // return $map_user;

      return view('frontend.assignments.show', compact('assignment'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $assignment = Assignment::find($id);

    $users = \DB::table('users')->lists('name', 'id');
    $maps = \DB::table('maps')->lists('name', 'id');

    return view('assignments.edit', compact('assignment', 'users', 'maps'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(AssignmentRequest $request, $id)
  {
    $assignment = Assignment::find($id);

    $assignment->update($request->all());

    return redirect('assignments');
}

public function finished()
{
    //
    $assignments = Assignment::finished()->get();


    return view('frontend.assignments.index', compact('assignments'));
}


public function unfinished()
{
    //
    $assignments = Assignment::unfinished()->get();


    return view('frontend.assignments.index', compact('assignments'));
}


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Assignment $assignment)
  {
      $assignment->destroy($request->all());
  }
  
   /**
     * Display a shared slip based on the assignment id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function showSlip($assignmentID, $slipID){
      
      // $slips = Slips::all();
      
      $assignment = Assignment::find($assignmentID);
      
      
      
      //check if the requested slip has been finished 
      if($assignment->finished_on == null){
      
        $slip = Slip::find($slipID);
        
      // dd($assignment, $slip);
      
        
        $slipName = $slip->name;
        
        $map_id = $slip->map_id;
        $myMap = Map::find($map_id);
       // dd($map_id);
  
        $streets = Street::all();
  
        /* Group our collection by Street ID */
        $assignedSlips = $slip->houses->groupBy('slip_id');
        //dd($slip->houses);
        //dd($assignedSlips);
        if (!$assignedSlips->isEmpty()) {
  
  
            $data = array();
          foreach ($assignedSlips as $slip) {
            $uniqueSlip = $slip->unique('slip_id')->all();
            $uniqueSlipId = $uniqueSlip[0]['slip_id'];
            //$slipName = $slip->name;
            //$slipId = $slip->id;
            
            // $mySlip['slip'][''] => $slipName, 'id' => $slipId];
          // dd($details);
            //$slipDetails['name']
            
            $assignedHouses = $myMap->houses->where('slip_id', $uniqueSlipId)->groupBy('street_id');
  
            foreach ($assignedHouses as $house) {
  
      /* Get a unique list of Street Ids so that later we can get the names*/
              $uniqueStreet = $house->unique('street_id')->where('slip_id', $uniqueSlipId);
  
                  foreach ($uniqueStreet as $str) {
                      $streetName = $streets->find($str->street_id)->name;
  
                  }
  
                  foreach ($house as $house) {
                    $houseNumber = $house->number;
                    $houseID = $house->id;
                    $bellFlatNo = $house->bell_flatno;
                    $houseStatus = $house->status;
                    $houseType = $house->type;
                    $houseDescription = $house->description;
//                     /* 
//                     * We need to get the assignment ID so that we can get the information of the house for the open assignment
//                     * otherwise it will give us info of the first house it finds, which will probably be a house belonging to a closed assignment
//                     */
                    $ass_id = Assignment::where('map_id', $myMap['id'])
                                        // ->where('user_id', Auth::user()->id)
                                        ->where('finished_on', NULL)
                                        ->value('id');
                   //   $ass_id = $assignmentID;
                    
//                     /* 
//                     * Using the assignment ID we just collected we can searh in the table assignments_houses for the correct entry for this
//                     * house, that way we will be displaying the correct status
//                     */
                     $assHouseStatus = AssignmentHouse::where('house_id', $houseID)
                                                   ->where('assignment_id', $ass_id)
                                                   ->value('status');
                                                  
                    $slipShared = AssignmentSlip::where('assignment_id', $ass_id)
                                                 ->where('slip_id', $slipID)
                                                 ->value('shared');
                    
                     if($slipShared == 1){                            
//                        $myData['slip'][$slipName]['id']=$slipID;
//                       $myData['slip'][$slipName]['street'][$streetName]['house']['id']= $houseID;
//                       $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['status']= $assHouseStatus;
//                       $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['number']= $houseNumber;
                          
                        $house = (array) $house;
                     // dd($house);

                      $newHouse=array(
                             "id" => $houseID,
                             "number" => $houseNumber,
                             "bellflat"  => $bellFlatNo,
                             "houseStatus" => $houseStatus,
                             "type"  => $houseType,
                             "description" => $houseDescription,
                             "assHouseStatus"  => $assHouseStatus
//                              "ass_id" => $house['assID']
                              );
                      //dd($slip);

                      //[$slip]['streets'][$value['streetName']]['houses'][]$houses;
                       $myData['slip'][$slipName]['id']=$slipID;
                      $myData['slip'][$slipName]['street'][$streetName]['house'][] = $newHouse;
                    
                      
                    }else{
                      //dd($slipShared);
                      $message = 'This slip hasn\'t been shared.';
                      return view('frontend.slips.show_error', compact('message'));
                      
                    }
                }
            }
          }
          // dd($myData);
          return view('frontend.slips.show', compact('myMap','myData', 'ass_id'));
        } else {
          $message = 'This slip hasn\'t been assigned';
          return view('frontend.slips.show_error', compact('message'));
        }
        
      }else{
        $message = 'This slip is no longer active';
        return view('frontend.slips.show_error', compact('message'));
      }
    
}

   public function houseStatusShared(AssignmentHouseRequest $request)
    {
    
       
       if(Request::ajax()) {
        $id = $request->input('id');
        $status = $request->input('status');
        $map_id = $request->input('map_id');
        $assignment_id = $request->input('ass_id');
        //dd($assignment_id);
        
        if($status==="true"){
          $status=1;
        } elseif($status==="false") {
          $status=0;
        }
        
        /* 
        * We need to find the assignment ID so that we know which house on the table assignments_houses to update
        * because we have different records for the same house ID (we can have some from closed assignments)
        */
        

        // $ass_id = Assignment::where('user_id', Auth::user()->id)
        //                     ->where('map_id', $map_id)
        //                     ->where('finished_on', NULL)
        //                     ->value('id');
        //dd($ass_id->id);
        
        
        AssignmentHouse::where('house_id', $id)
                        ->where('assignment_id', $assignment_id)
                        ->update(['status' => $status]);
        
        
       }

    }

}
