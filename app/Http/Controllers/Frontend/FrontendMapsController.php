<?php

namespace App\Http\Controllers\Frontend;

use DB;
use App\Map;
use App\Street;
use App\Helpers\helpers;
use App\Slip;
use App\User;
use App\Assignment;
use App\House;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Requests\MapRequest;

use Illuminate\Http\Request;

class FrontendMapsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $map= Map::all();
        $unavailable = Map::unavailable()->get();
        $maps=$map->diff($unavailable)->sortBy('number');
        

        return view('frontend.maps.index', compact('maps'));
    }
    
    public function my_maps()
    {
       
        $maps=Map::Maps()->get();
        $assignments=Assignments()->get;
        
        return view('frontend.maps.index', compact('maps', 'assignments'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // $slips = Slip::lists('name', 'name');
      //
      //   return view('maps.create', compact('slips'));

      return view('maps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MapRequest $request)
    {
          //only continues below if validation doesn't fail

          // dd($request->input('slips'));
        Map::create($request->all());

        return redirect('admin/maps')->with('message', 'The map has been created!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Map $request, $id)
    {

      $maps = Map::all();

      $map = $request->find($id);
      $myMap['name'] = $map->name;
      $myMap['id'] = $map->id;
      $myMap['number'] = $map->number;


     // $mapName = $map->find($myMap['id'])->name;

   
        $slips = Slip::all();

      $streets = Street::all();

      /* Group our collection by Street ID */
      $slipsExist = $map->slips;
      $assignedSlips = $map->houses->groupBy('slip_id');
      //dd($assignedSlips);
      if (!$slipsExist->isEmpty()) {


          $data = array();
        foreach ($slipsExist as $slip) {
          //$uniqueSlip = $slip->unique('slip_id')->all();
          //$uniqueSlipId = $uniqueSlip[0]['id'];
          //dd($slip);
          //$slipName = $slips->find($uniqueSlipId)->name;
          $uniqueSlipId = $slip->id;
          $slipName = $slip->name;
      
          $assignedHouses = $map->houses->where('slip_id', $uniqueSlipId)->groupBy('street_id');
          //dd($assignedHouses);
          if (!$assignedHouses->isEmpty()) {
             // If there are houses for this slip           
              //dd("I'm in");
              foreach ($assignedHouses as $house) {

        /* Get a unique list of Street Ids so that later we can get the names*/
                $uniqueStreet = $house->unique('street_id')->where('slip_id', $uniqueSlipId);

                    foreach ($uniqueStreet as $str) {
                        $streetName = $streets->find($str->street_id)->name;

                    }

                    foreach ($house as $value) {
                      
                      //$myHouse[$slipName][$streetName]['house'] = $test->number;
                      $houseID = $value->id;
                      $houseNumber = $value->number;
                      $houseType = $value->type;
                      $houseStatus = $value->status;
                      $houseDescription = $value->description;
                      $houseBellFlat = $value->bell_flatno;
                      
                      $myData['slip'][$slipName]['street'][$streetName]['house'][] = array('id' => $houseID, 'number' => $houseNumber,
                      'type' => $houseType, 'status' => $houseStatus, 'description' => $houseDescription, 'bellflat' => $houseBellFlat);


                      //$myData['slip'][$slipName]['street'][$streetName]['house'][] = $houseNumber;
                      //array_push($myData, $myHouse);
                      }
                  }
              
          
          } else {
            //dd("I'm in!");
            $myData['slip'][$slipName] = $slipName;
          } 
          
         }
         //dd($myData);

          $myData=collect( $myData['slip'] )->paginate( 2 );
        //dd($myData);
         return view('frontend.maps.show', compact('myMap','myData'));
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

        $map = Map::find($id);

        return view('maps.edit', compact('map'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MapRequest $request, $id)
    {
      // $map = Map::all();

        $map = Map::find($id);

      // return $map;

      //$map->update($request->all());

        $map->update($request->all());

        //$username = (!$request->input('username') ? [] : $request->input('username'));

        //$assigned_on = (!$request->input('assigned_on') ? [] : $request->input('assigned_on'));

        //$pivotData = array('map_id'=>$id, 'user_id'=>$username);

        // return $pivotData;

        //$map->users()->sync($pivotData);

        return redirect('maps');
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

    public function available()
    {
        //
        //$maps = Map::available()->get();

        $map= Map::all();
        $unavailable = Map::unavailable()->get();
        $maps=$map->diff($unavailable);


        return view('maps.index', compact('maps'));
    }


    public function unavailable()
    {
        //
        $maps = Map::unavailable()->get();

        //return compact('maps');

        return view('maps.index', compact('maps'));
    }
    
    //public function shareSlip()

 }
 

// class MapsController extends Controller {

//     public function index()
//     {

//         $grid = \DataGrid::source(Map::with());

//         $grid->add('id','ID', true)->style("width:100px");
//         $grid->add('name','Name');
//         // $grid->add('{!! str_limit($body,4) !!}','Body');
//         // $grid->add('{{ $author->fullname }}','Author', 'author_id');
//         //$grid->add('{{ implode(", ", $assignments->lists("name")->all()) }}','Assignments');

//         $grid->edit('/maps/edit', 'Edit','show|modify');
//         $grid->link('/maps/edit',"New Article", "TR");
//         $grid->orderBy('id','desc');
//         $grid->paginate(10);

//         $grid->row(function ($row) {
//           if ($row->cell('id')->value == 20) {
//               $row->style("background-color:#CCFF66");
//           } elseif ($row->cell('id')->value > 15) {
//               $row->cell('title')->style("font-weight:bold");
//               $row->style("color:#f00");
//           }
//         });

//         return  view('maps.test', compact('grid'));
//     }
// }