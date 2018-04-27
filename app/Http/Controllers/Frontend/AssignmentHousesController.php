<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Map;
use App\Street;
use App\Slip;
use App\User;
use App\Assignment;
use App\AssignmentHouse;
use App\House;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AssignmentHousesController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Map $request, $id)
    {
        
          //dd($mapID);
          
          $maps = Map::all();

          $map = $request->find($id);
          $mapName = $map->name;
          
         
          
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
                
              //$assignedHouses = $map->assignmenthouse->where    
          
              //$assignedHouses = $map->houses->where('slip_id', $uniqueSlipId)->groupBy('street_id');
    
              foreach ($assignedHouses as $house) {
    
        /* Get a unique list of Street Ids so that later we can get the names*/
                $uniqueStreet = $house->unique('street_id')->where('slip_id', $uniqueSlipId);
    
                    foreach ($uniqueStreet as $str) {
                        $streetName = $streets->find($str->street_id)->name;
    
                    }
    
                    foreach ($house as $item) {
                      //$myHouse[$slipName][$streetName]['house'] = $item->number;
                      $houseNumber = $item->number;
                      $myData['slip'][$slipName]['street'][$streetName]['house'][ ] = $houseNumber;
                      //array_push($myData, $myHouse);
                      }
                  }
             }
             return view('frontend.maps.show', compact('mapName','myData'));
          } else {
            return view('frontend.maps.show_error');
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
