<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Slip;
use App\User;
use App\Map;
use App\House;
use App\Street;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Requests\SlipRequest;
use Session;

class SlipsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // $slips = Slip::all();
       $maps = Map::all();

      $slips = Slip::paginate(15);
      
      return view('admin.slips.index', compact('slips', 'maps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $maps = \DB::table('maps')->lists('name', 'id');

      $slips = Slip::all();
      return view('admin.slips.create', compact('slips', 'maps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SlipRequest $request)
    {
      Slip::create($request->all());

      return redirect('admin/slips')->with('message', 'The slip has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slip $request, $id)
    {
      $myData = array();
      $slip = $request->find($id);
      $slipName = $slip->name;
      $houses = House::all();
      $streets = Street::all();
    //  $house = \DB::table('houses')->lists('number', 'id');
    //echo($slip);
    $assignedHouses = $slip->houses->groupBy('street_id');
    
    if(!$assignedHouses->isEmpty()){
    //->where('slip_id', $id)->groupBy('street_id');
    //dd($assignedHouses);
    foreach ($assignedHouses as $house) {

/* Get a unique list of Street Ids so that later we can get the names*/
      $uniqueStreet = $house->unique('street_id');

          foreach ($uniqueStreet as $str) {
              $streetName = $streets->find($str->street_id)->name;

          }
          foreach ($house as $test) {
            $houseNumber = $test->number;
            $myData['street'][$streetName]['house'][ ] = $houseNumber;
            }
         }
         
         // dd($myData);
      return view('admin.slips.show', compact('slipName','myData'));
    
      
    } else {
  
        Session::flash('flash_message', 'There are no Streets assigned to this slip!');
        Session::flash('flash_type', 'alert-error');
        return redirect('admin/slips');
      
    }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slip = Slip::find($id);

        $maps = \DB::table('maps')->lists('name', 'id');

        return view('admin.slips.edit', compact('slip', 'maps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SlipRequest $request, $id)
    {
      $slip = Slip::find($id);

      $slip->update($request->all());

      return redirect('admin/slips');
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
        Slip::destroy($id);

        Session::flash('flash_message', 'Slip deleted!');

        return redirect('admin/slips');
    }

}
