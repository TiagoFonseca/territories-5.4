<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DB;
use Mail;

use Session;
use App\User;
use App\Assignment;
use App\MapRequest;
use App\Map;
use App\AssignmentHouse;
use App\AssignmentSlip;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Response;


class MapRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maprequests = MapRequest::where('status', 'pending')->paginate(15);
        
        return view('admin.maprequests.index', compact('maprequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        // dd($request->all());
        
        
        /* First we edit the entry on the Map Requests database to Accepted or Denied */
        /* Get the status */
        $user = User::find($request->user_id);
        $map = Map::find($request->map_id);
        
        //dd($user);
        $status = $request->input('status');
        
       // Assignment::create($request->all());
        DB::table('map_requests')
            ->where('id', $request->input('id'))
            ->update(['status' => $status]);
            
        
        
        if ($status=="Accepted"){ 
            /* Because the request was accepted we now create an entry on the Assignments database */
            
            
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
            
            // And now we populate the table AssignmentHouse with all the houses we have on the requested map
               // dd($map->slips);
  
          
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
            
            
            
            Session::flash('flash_message', 'Assignment added');
            Session::flash('flash_type', 'alert-success');
            
            Session::flash('flash_message', 'The map has been successfully assigned');
            Session::flash('flash_type', 'alert-success');
        }else{
            Session::flash('flash_message', 'The request has been rejected');
            Session::flash('flash_type', 'alert-success');
            
        }        
        
        // Send email to user 
//          Mail::send('emails.mapRequestUserResponse', ['user' => $user, 'map' => $map, 'status'=> $status], function ($m) use ($user, $map, $status) 
//         {
//             $m->from('hello@app.com', 'Your Application');

//             $m->to($user->email, $user->name)->subject('Your request has been refused.');
            
//         });
        
        return redirect('admin/map-requests');
        }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
