<?php

namespace App\Http\Controllers\Frontend;

use DB;
use App\Map;
use App\Street;
use Input;
use App\Slip;
use App\User;
use App\Assignment;
use App\House;
use App\AssignmentHouse;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Requests\MapRequest;
use App\Http\Requests\AssignmentHouseRequest;

use Request;
//use Illuminate\Http\Request;

class FrontendSlipsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maps=Map::MyMaps()->get();
        
        return view('frontend.slips.index', compact('maps'));
    }
    
    // public function my_maps()
    // {
       
    //     $maps=Map::MyMaps()->get();
        
    //     return view('frontend.my-maps.index', compact('maps'));
    // }
    

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

      return view('my-maps.create');
    }

    /**
     *  
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **/
    
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
   public function show(Slip $request, $id){
      
      // $slips = Slips::all();
    
      $slip = $request->find($id);
      $slipName = $slip->name;
      
      $map_id = $slip->map_id;
      $myMap = Map::find($map_id);
     // dd($map);

      $streets = Street::all();

      /* Group our collection by Street ID */
      $assignedSlips = $slip->houses->groupBy('slip_id');
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

                foreach ($house as $item) {
                  $houseNumber = $item->number;
                  $houseID = $item->id;
                  
                  /* 
                  * We need to get the assignment ID so that we can get the information of the house for the open assignment
                  * otherwise it will give us info on the first if finds, which will be a house belonging to a closed assignment
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
                                                ->where('assignment_id', $ass_id)
                                                ->value('status');
                                                
                    
                    $myData['slip'][$slipName]['street'][$streetName]['house']['id']= $houseID;
                    $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['status']= $houseStatus;
                    $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['number']= $houseNumber;
                    

                    
                  }
              }
        }
        // dd($myData);
        return view('frontend.slips.show', compact('myMap','myData'));
      } else {
        return view('frontend.slips.show_error');
      }
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {

    //     $map = Map::find($id);

    //     return view('my-maps.edit', compact('map'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(MapRequest $request, $id)
    // {
    //   // $map = Map::all();

    //     $map = Map::find($id);

    //   // return $map;

    //   //$map->update($request->all());

    //     $map->update($request->all());

    //     //$username = (!$request->input('username') ? [] : $request->input('username'));

    //     //$assigned_on = (!$request->input('assigned_on') ? [] : $request->input('assigned_on'));

    //     //$pivotData = array('map_id'=>$id, 'user_id'=>$username);

    //     // return $pivotData;

    //     //$map->users()->sync($pivotData);

    //     return redirect('my-maps');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//     public function destroy($id)
//     {
//         //
//     }
    
//     public function available()
//     {
//         //
//         //$maps = Map::available()->get();

//         $map= Map::all();
//         $unavailable = Map::unavailable()->get();
//         $maps=$map->diff($unavailable);


//         return view('my-maps.index', compact('maps'));
//     }


//     public function unavailable()
//     {
//         //
//         $maps = Map::unavailable()->get();

//         //return compact('maps');

//         return view('my-maps.index', compact('maps'));
//     }
    
//     public function houseStatus(AssignmentHouseRequest $request)
//     {
    
  
//       if(Request::ajax()) {
//         $id = $request->input('id');
//         $status = $request->input('status');
//         $map_id = $request->input('map_id');
        
//         if($status==="true"){
//           $status=1;
//         } elseif($status==="false") {
//           $status=0;
//         }
        
//         /* 
//         * We need to find the assignment ID so that we know which house on the table assignments_houses to update
//         * because we have different records for the same house ID (we can have some from closed assignments)
//         */
        

//         $ass_id = Assignment::where('user_id', Auth::user()->id)
//                             ->where('map_id', $map_id)
//                             ->where('finished_on', NULL)
//                             ->value('id');
//         //dd($ass_id->id);
        
        
//         AssignmentHouse::where('house_id', $id)
//                         ->where('assignment_id', $ass_id)
//                         ->update(['status' => $status]);
        
        
//       }

//     }
    
    

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

//         return  view('maps.item', compact('grid'));
//     }
// }