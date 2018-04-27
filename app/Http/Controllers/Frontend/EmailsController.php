<?php

namespace App\Http\Controllers\Frontend;

use Mail;
use App\User;
//use App\Http\Requests;
//use App\Http\Requests\EmailRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Map;
//use App\Http\Controllers\Controller;
use Auth;

use Session;

class EmailsController extends Controller {
    
    /**
     * Send an e-mail request from user to admin for a certain territory to be assigned to him
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function sendMapRequest(Request $request)
    {
        //$user = User::findOrFail($id);
        
        $user = Auth::user();
        $id = $request->input('map');
        $maps = Map::all();
        
        $map = $maps->find($id);
        
        //dd($map);

        Mail::send('emails.mapRequest', ['user' => $user, 'map' => $map], function ($m) use ($user, $map) 
        {
            $m->from('hello@app.com', 'Your Application');

            $m->to('tmsfonseca@gmail.com', 'Admin')->subject('Requests map number');
            
        });
        
        Session::flash('flash_message', 'Your request has been sent, you will receive a reply shortly');
        Session::flash('flash_type', 'alert-success');
        
        return back();
        //->with("message", "Your request has been sent, you will receive a reply shortly.");
   
   
    }
    
    
}