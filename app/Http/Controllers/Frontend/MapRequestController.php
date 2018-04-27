<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use Auth;

use App\Map;

use Mail;
use App\User;
use Session;

use App\MapRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MapRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     
      $user = Auth::user();   
     //dd($request->all());     
      $maprequest = new  MapRequest;
      //MapRequest::create($request->all());
      $maprequest->user_id = $user->id;
      $maprequest->map_id = $request->map;
      $maprequest->status = "pending";
      $maprequest->save();
      
      $user = Auth::user();
        $id = $request->input('map');
        $maps = Map::all();
        
        $map = $maps->find($id);
        
        //dd($map);

//         Mail::send('emails.mapRequest', ['user' => $user, 'map' => $map], function ($m) use ($user, $map) 
//         {
//             $m->from('hello@app.com', 'Your Application');

//             $m->to('tmsfonseca@gmail.com', 'Admin')->subject('Requests map number');
            
//         });
        
        Session::flash('flash_message', 'Your request has been sent, you will receive a reply shortly');
        Session::flash('flash_type', 'alert-success');
        
        return back();
        //->with("message", "Your request has been sent, you will receive a reply shortly.");

      //return redirect('assignments')->with('message', 'The assignment has been created!');
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
    
     public function sendMapRequest(Request $request)
    {
        //$user = User::findOrFail($id);
        
        $user = Auth::user();
        $id = $request->input('map');
        $maps = Map::all();
        
        $map = $maps->find($id);
        
        //dd($map);

//         Mail::send('emails.mapRequest', ['user' => $user, 'map' => $map], function ($m) use ($user, $map) 
//         {
//             $m->from('hello@app.com', 'Your Application');

//             $m->to('tmsfonseca@gmail.com', 'Admin')->subject('Requests map number');
            
//         });
        
        Session::flash('flash_message', 'Your request has been sent, you will receive a reply shortly');
        Session::flash('flash_type', 'alert-success');
        
        return back();
        //->with("message", "Your request has been sent, you will receive a reply shortly.");
   
   
    }
}
