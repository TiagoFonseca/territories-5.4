<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Slip;
use App\User;
use App\Assignment;
use App\House;
use App\Street;
use App\Map;
use Illuminate\Http\Request;
use App\Http\Requests\MapRequest;
use Carbon\Carbon;
use Session;

class MapsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $maps = Map::paginate(15);

        return view('admin.maps.index', compact('maps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.maps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(MapRequest $request)
    {
        
       $data = $request->all();
       
        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            //
            $picture = $request->file('picture');
            
            $destinationPath = base_path() . '/public/images/maps/';
            
            $fileName =  $request->number . '-cover.' .$request->file('picture')->getClientOriginalExtension();
            
            $picture->move($destinationPath, $fileName);
            // dd($destinationPath . $fileName);
            
            
            $data['picture'] = '/images/maps/' . $fileName;
        

        } else {
            Session::flash('flash_message', 'Picture not found!');
            Session::flash('flash_type', 'alert-error');
            return redirect('admin/maps');

        }
        

        // dd($data);
        
        Map::create($data);

        Session::flash('flash_message', 'Map added!');
        Session::flash('flash_type', 'alert-success');
        return redirect('admin/maps');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    // public function show($id)
    // {
    //     $map = Map::findOrFail($id);

    //     return view('admin.maps.show', compact('map'));
    // }
    
       /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Map $request, $id)
    {

    //   $maps = Map::all();

    //   $map = $request->find($id);
    $map = Map::findOrFail($id);
    $mapName = $map->name;

    $slips = Slip::all();
    $streets = Street::all();

    /* Group our collection by Street ID */
    $assignedSlips = $map->houses->groupBy('slip_id');
    $hasSlips = $map->slips;
    //dd($assignedSlips);
    if ($hasSlips->isEmpty()) {
        
        Session::flash('flash_message', 'There are no Slips assigned to this map!');
        Session::flash('flash_type', 'alert-error');
        return redirect('admin/maps');
        
      } elseif($assignedSlips->isEmpty()) {
            
            Session::flash('flash_message', 'The slip or slips assigned to this map have no houses!');
            Session::flash('flash_type', 'alert-error');
            return redirect('admin/maps');
          
      } else {
        foreach ($assignedSlips as $slip) {
          $uniqueSlip = $slip->unique('slip_id')->all();
          $uniqueSlipId = $uniqueSlip[0]['slip_id'];
          $slipName = $slips->find($uniqueSlipId)->name;

      
          $assignedHouses = $map->houses->where('slip_id', $uniqueSlipId)->groupBy('street_id');
          
          //dd($assignedHouses);
          
          foreach ($assignedHouses as $house) {

    /* Get a unique list of Street Ids so that later we can get the names*/
            $uniqueStreet = $house->unique('street_id')->where('slip_id', $uniqueSlipId);

                foreach ($uniqueStreet as $str) {
                    $streetName = $streets->find($str->street_id)->name;

                }

                foreach ($house as $test) {

                  $houseNumber = $test->number;
                  $houseType = $test->type;
                  $houseStatus = $test->status;
                  $houseDescription = $test->description;
                  $houseBellFlat = $test->bellflatno;

                  
                  $myData['slip'][$slipName]['street'][$streetName]['house'][] = array('number' => $houseNumber,
                  'type' => $houseType, 'status' => $houseStatus, 'description' => $houseDescription, 'bellflat' => $houseBellFlat);
                  
                //   $myData['slip'][$slipName]['street'][$streetName]['house'][] = $houseType;
                //   $myData['slip'][$slipName]['street'][$streetName]['house'][] = $houseStatus;
                //   $myData['slip'][$slipName]['street'][$streetName]['house'][] = $houseDescription;
                //   $myData['slip'][$slipName]['street'][$streetName]['house'][] = $houseBellFlat;

                  }
              }    
         
         }
          //dd($myData);
         return view('admin.maps.show', compact('mapName','myData'));
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
        $map = Map::findOrFail($id);

        return view('admin.maps.edit', compact('map'));
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
        
        $map = Map::findOrFail($id);
        $map->update($request->all());

        Session::flash('flash_message', 'Map updated!');
        Session::flash('flash_type', 'alert-success');

        return redirect('admin/maps');
    }
    
    public function mapBuilder(){
        return view('admin.maps.builder');
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
        Map::destroy($id);

        Session::flash('flash_message', 'Map deleted!');
        Session::flash('flash_type', 'alert-success');
        return redirect('admin/maps');
    }

}
