<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\AssignmentHouse;
use App\Assignment;
use App\AssignmentSlip;
use App\Map;
use App\Slip;
use App\Street;
use Auth;
use Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class AssignmentsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $assignments = Assignment::paginate(15);
        
        // foreach ($assignments as $item) {
        //      echo $item->user->name;
        //      echo $item->map->number;
        //      echo $item->map->name;
        //      echo $item->assigned_on->diffForHumans();
        //      echo $item->finished_on;
        // }
        if($assignments){
            return view('admin.assignments.index', compact('assignments'));
        }else{
            Session::flash('flash_message', 'There are no assignments to display');
            Session::flash('flash_type', 'alert-error');
            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        
      $users = \DB::table('users')->pluck('name', 'id');
      $maps = \DB::table('maps')->pluck('number', 'id');
    //dd($maps);
      return view('admin.assignments.create', compact('users', 'maps'));
      //  return view('admin.assignments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        $data = new Assignment;
        
        $data->map_id = $request->map_id;
        $data->user_id = $request->user_id;
        $data->assigned_on = $request->assigned_on;
        $data->save();
        
        /* 
        * Need to find out the id of the assignment that was just created
        * we can get that from the session by using 'Response'
        */
        
        Response::json(array('success' => true, 'last_insert_id' => $data->id), 200);
        
       //alert($data->id);
        
        $map = Map::find($request->map_id);
        //$nrHouses = $map->houses->count();
        $slips = $map->slips;
        
        //dd($slip_id);
          //      Response::json(array('success' => $slip_id), 200);

        
        foreach ($map->houses as $house) {      
            $assHouses = new AssignmentHouse;
            $assHouses->assignment_id = $data->id;
            $assHouses->house_id = $house->id;
            $assHouses->status = 0;
            $assHouses->save();
        }
        
         foreach ($map->slips as $slip) {
             $assSlips = new AssignmentSlip;
             $assSlips->assignment_id = $data->id;
             $assSlips->slip_id = $slip->id;
             $assSlips->shared = 0;
             $assSlips->save();
         }
        
       // dd($nrHouses);
        
       // $assHouses->assignment_ID = $data->id;
        
        
        
        Session::flash('flash_message', 'Assignment added!');
        Session::flash('flash_type', 'alert-success');
        
        return redirect('admin/assignments');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $assignment = Assignment::findOrFail($id);

        // return view('admin.assignments.show', compact('assignment'));
        
        //dd($mapID);
          
        $map = $assignment->map;
        $myMap['name'] = $map->name;
        $myMap['id'] = $map->id;
          
         
          
        $slips = Slip::all();

      $streets = Street::all();

      /* Group our collection by Street ID */
      $assignedSlips = $map->houses->groupBy('slip_id');
      
      if (!$assignedSlips->isEmpty()) {


          $data = array();
        foreach ($assignedSlips as $slip) {
          $uniqueSlip = $slip->unique('slip_id')->all();
          $uniqueSlipId = $uniqueSlip[0]['slip_id'];
          $slipName = $slips->find($uniqueSlipId)->name;

      
          $assignedHouses = $map->houses->where('slip_id', $uniqueSlipId)->groupBy('street_id');

          foreach ($assignedHouses as $house) {

    /* Get a unique list of Street Ids so that later we can get the names*/
            $uniqueStreet = $house->unique('street_id')->where('slip_id', $uniqueSlipId);

                foreach ($uniqueStreet as $str) {
                    $streetName = $streets->find($str->street_id)->name;

                }

                foreach ($house as $item) {
                  //$myHouse[$slipName][$streetName]['house'] = $item->number;
                  $houseNumber = $item->number;
                  $houseID = $item->id;
                  
                  /* 
                  * We need to get the assignment ID so that we can get the information of the house for the open assignment
                  * otherwise it will give us info on the first ID it finds, which will be a house belonging to a closed assignment
                  */
                  $ass_id = Assignment::where('map_id', $myMap['id'])
                                      ->where('user_id', Auth::user()->id)
                                      ->where('finished_on', NULL)
                                      ->value('id');
                  /* 
                  * Using the assignment ID we just collected we can searh in the table assignments_houses for the correct entry for this
                  * house, that way we will be displaying the correct status
                  */
                  $houseStatus = AssignmentHouse::where('house_id', $houseID)
                                                ->where('assignment_id', $id)
                                                ->value('status');
                  //dd($houseID, $id);                             
                 // print($houseStatus);                       
                 // $statusAssHouses = $houseStatus;
                 // $statusAssHouses = $allAssHouses->status;
  
                  
                  //dd($statusAssHouses);
                  //$myData['slip'][$slipName]['street'][$streetName]['house'][$houseNumber]['status'] = $statusAssHouses; //['id'][] = $houseID;
                    $myData['slip'][$slipName]['street'][$streetName]['house']['status'] = $houseStatus;
                    $myData['slip'][$slipName]['street'][$streetName]['house']['number'] = $houseNumber;
                    $myData['slip'][$slipName]['street'][$streetName]['house']['id'] = $houseID;
                  //array_push($myData, $myHouse);
                  }
              }
         }
         //dd($myMap);
         return view('admin.assignments.show', compact('myMap','myData'));
      } else {
        return view('admin.assignments.show_error');
      }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $assignment = Assignment::findOrFail($id);

        return view('admin.assignments.edit', compact('assignment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        $assignment = Assignment::findOrFail($id);
        $assignment->update($request->all());

        Session::flash('flash_message', 'Assignment updated!');

        return redirect('admin/assignments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Assignment::destroy($id);

        Session::flash('flash_message', 'Assignment deleted!');

        return redirect('admin/assignments');
    }

}
