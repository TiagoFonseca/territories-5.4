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
use App\AssignmentSlip;
use App\AssignmentSlipPublisher;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Requests\MapRequest;
use App\Http\Requests\AssignmentHouseRequest;
use App\Http\Requests\AssignmentSlipRequest;
use App\Bitly;
use App\Helpers\helpers;
use Watson\Autologin\Facades\Autologin;
//use Illuminate\Support\Facades\Crypt;
use Junity\Hashids\Facades\Hashids;

use Request;
//use Illuminate\Http\Request;

class FrontendMyMapsController extends Controller
{

    public function unflatten($arr)
    {
        $result = array();

        foreach($arr as $key => $value) {
            $keys = explode(".", $key); //potentially other separator
            $lastKey = array_pop($keys);

            $node = &$result;
            foreach($keys as $k) {
                if (!array_key_exists($k, $node))
                    $node[$k] = array();
                $node = &$node[$k];
            }

            $node[$lastKey] = $value;
        }

        return $result;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maps=Map::MyMaps()->get();

        return view('frontend.my-maps.index', compact('maps'));
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


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Map $request, $id)
    {
      //$mapID = request->find($id);
      $myMap = Map::find($id);
      $assignmentID = $myMap->assignments->where("finished_on", NULL)->first()->id;
      //dd("MAP ".$myMap." ASSIGNMENT: ". $assignmentID );
      $results = DB::select("SELECT assignments.id as assID, assignments_slips.shared as shared, maps.number as mapNumber, slips.id as slipID, slips.name as slipName, streets.name as streetName, houses.id as houseID, houses.number as houseNumber, houses.bell_flatno as houseBellFlat, houses.status as houseStatus, houses.type as houseType, houses.description as houseDesc, assignments_houses.status as assHouseStatus
      FROM assignments, maps, slips, streets, houses, assignments_houses, assignments_slips
      WHERE (assignments.id ='$assignmentID' )
      AND (assignments_slips.slip_id = slips.id AND( assignments_slips.assignment_id = '$assignmentID'))
      AND maps.id = assignments.map_id
      AND slips.map_id = maps.id
      AND (
      houses.slip_id = slips.id
      AND houses.street_id = streets.id
      )
      AND (
      assignments_houses.assignment_id = assignments.id
      AND assignments_houses.house_id = houses.id
      );
      ");

      $sourceData = collect($results);

      $totalSlips = $sourceData->unique('slipID')->map(function($item, $key) {
         return [
             "slipID" => $item->slipID,
              "slipName"  => $item->slipName,
              "shared" => $item->shared
              ];
      })->values();
      $mapNumber = $sourceData->unique('mapNumber')->pluck('mapNumber');
      $myData=[];
      $totalSlips = $totalSlips->sortBy('slipName');


      foreach($totalSlips as $slip) {
        $newSourceData = $sourceData->where('slipID', $slip['slipID'])->values();
        $streets = $newSourceData->unique('streetName')->pluck('streetName')->map(function($streetName) {
          return [
             "streetName" => $streetName
         ];
        })->values();

        // Get information on how many times the slip has been worked on
        $ass_slip_id = AssignmentSlip::where('slip_id', $slip['slipID'])
                                            ->where('assignment_id', $assignmentID)
                                            ->get();
        $workedOn = AssignmentSlipPublisher::where('assignment_slip_id', $ass_slip_id[0]->id)->get();
    //dd($workedOn);
        foreach($streets as $street){
          $houses = $newSourceData->where('streetName', $street['streetName'])->toArray();

          foreach($houses as $house){
            $house = (array) $house;

           //Obscure the assignment ID and the slip ID
           $ass_id_encrypted = Hashids::encode($house['assID']);
           $slipId_encrypted = Hashids::encode($slip['slipID']);

          //Generate autologin token for user "guest"
          $guest = User::where('name', 'guest')->first();
          //dd($guest);
          $autologin_url = Autologin::to($guest, '/assignments/'.$ass_id_encrypted.'/slips/'.$slipId_encrypted);

        //  dd($autologin_url);
            $newHouse=array(
                   "id" => $house['houseID'],
                   "number" => $house['houseNumber'],
                   "bellflat"  => $house['houseBellFlat'],
                   "houseStatus" => $house['houseStatus'],
                   "type"  => $house['houseType'],
                   "description" => $house['houseDesc'],
                   "assHouseStatus"  => $house['assHouseStatus'],
                   "ass_id" => $house['assID'],
                   "ass_id_encrypted" => $ass_id_encrypted,
                   "autologin_url" => $autologin_url
                    );
            //dd($slip);
            // url = "/assignments/".$ass_id_encrypted."/slips/".$slipId_encrypted


            //[$slip]['streets'][$value['streetName']]['houses'][]$houses;
            $myData['slip'][$slip['slipName']]['id']=$slip['slipID'];
            $myData['slip'][$slip['slipName']]['id_encrypted']=$slipId_encrypted;
            $myData['slip'][$slip['slipName']]['workedOn']=$workedOn;

            $myData['slip'][$slip['slipName']]['shared']=$slip['shared'];
            $myData['slip'][$slip['slipName']]['street'][$street['streetName']]['house'][]=$newHouse;


          }
        }

      }
      //dd($myData);
      $mapID = $mapNumber[0];
     // $myData = collect($myData);
     $myData=collect( $myData['slip'] )->paginate( 2 );
         //dd($myData);


      return view('frontend.my-maps.show', compact('myMap','myData'));


      //dd(collect($results)->unique('slipID'));
      //$test = collect($results)->map(function($x){ return (array) $x; })->toArray();
      /*dd($results);
      $myArray = [];
      $temp=[];
      foreach ($test as $key => $value) {
         $keys = explode("_", $key); //potentially other separator
         $lastKey = array_pop($keys);

            $node = &$result;
            foreach($keys as $k) {
                if (!array_key_exists($k, $node))
                    $node[$k] = array();
                $node = &$node[$k];
            }

            $node[$lastKey] = $value;

      }

     dd($keys);

      $myData=collect($results);
      $final = [];
      $uniqueSlips=$myData->unique('slipID');
      $uniqueStreets=$uniqueSlips->unique('streetName');

      $grouped = $myData->mapToGroups(function ($item, $key) {
          return [$item->slipName => [
                      $item->streetName => [
                        'houseNumber' => $item->houseNumber,
                        'houseBellFlat'=> $item->houseBellFlat,
                        'houseStatus' => $item->houseStatus,
                        'houseType' => $item->houseType,
                        'houseDesc' => $item->houseDesc,
                        'assHouseStatus' => $item->assHouseStatus
                      ],
                    ],
                  ];

      });

      dd($grouped);

      //$myData = collect($assignedMap)->paginate(3);
      foreach($uniqueSlips as $uSlip){
         foreach($uniqueStreets as $uStreet){

             //$final['slip'][$uSlip->slipID]=['slipID' => $uSlip->slipID, 'slipName' => $uSlip->slipName];
             $final['slip'][$uSlip->slipID]['street'][$uStreet->streetName]['house'][]=['streetName' => 'test'];
         }

      }

     return view('frontend.my-maps.show', compact('final'));

       //dd($final);

      /*
      $myData = collect($results);
      //dd($myData);
      $grouped = $myData->mapToGroups(function ($item, $key) {
          return [$item->slipName => [
                      $item->streetName => [
                        'houseNumber' => $item->houseNumber,
                        'houseBellFlat'=> $item->houseBellFlat,
                        'houseStatus' => $item->houseStatus,
                        'houseType' => $item->houseType,
                        'houseDesc' => $item->houseDesc,
                        'assHouseStatus' => $item->assHouseStatus
                      ],
                    ],
                  ];

      });

      dd($grouped->toArray());
      */


//      dd($results);

     /* $maps = Map::all();

      $map = $request->find($id);
      $myMap['name'] = $map->name;
      $myMap['id'] = $map->id;

      $slips = Slip::all();

      $streets = Street::all();

      /* Group our collection by Street ID */
      $assignedSlips = $map->houses->groupBy('slip_id');

      /* Test to improve database performance */


      /*  */

      if (!$assignedSlips->isEmpty()) {


          $data = array();
        foreach ($assignedSlips as $slip) {
          $uniqueSlip = $slip->unique('slip_id')->all();
          $uniqueSlipId = $uniqueSlip[0]['slip_id'];
          $slipName = $slips->find($uniqueSlipId)->name;
          $slipId = $slips->find($uniqueSlipId)->id;

          // $mySlip['slip'][''] => $slipName, 'id' => $slipId];
         // dd($details);
          //$slipDetails['name']

          $assignedHouses = $map->houses->where('slip_id', $uniqueSlipId)->groupBy('street_id');

          foreach ($assignedHouses as $house) {

    /* Get a unique list of Street Ids so that later we can get the names*/
            $uniqueStreet = $house->unique('street_id')->where('slip_id', $uniqueSlipId);

                foreach ($uniqueStreet as $str) {
                    $streetName = $streets->find($str->street_id)->name;

                }

                foreach ($house as $item) {
                  $houseID = $item->id;
                  $houseNumber = $item->number;
                  $houseType = $item->type;
                  $houseStatus = $item->status;
                  $houseDescription = $item->description;
                  $houseBellFlat = $item->bellflatno;


                  /*
                  * We need to get the assignment ID so that we can get the information of the house for the open assignment
                  * otherwise it will give us info on the first if finds, which will be a house belonging to a closed assignment
                  */
                  $ass_id = Assignment::where('map_id', $myMap['id'])
                                      ->where('user_id', Auth::user()->id)
                                      ->where('finished_on', NULL)
                                      ->value('id');

                  //dd($ass_id_encrypted);
                  /*
                  * Using the assignment ID we just collected we can searh in the table assignments_houses for the correct entry for this
                  * house, that way we will be displaying the correct status
                  */
                  $assHouseStatus = AssignmentHouse::where('house_id', $houseID)
                                                ->where('assignment_id', $ass_id)
                                                ->value('status');

                  $slipShared = AssignmentSlip::where('assignment_id', $ass_id)
                                                ->where('slip_id', $slipId)
                                                ->value('shared');



                    $myData['slip'][$slipName]['id']=$slipId;
                    $myData['slip'][$slipName]['shared']=$slipShared;
                    $myData['slip'][$slipName]['street'][$streetName]['house'][] = array('id' => $houseID, 'number' => $houseNumber,
                  'type' => $houseType, 'houseStatus' => $houseStatus, 'assStatus' => $assHouseStatus, 'description' => $houseDescription, 'bellflat' => $houseBellFlat, 'assHouseStatus' => $assHouseStatus, 'ass_id' => $ass_id, 'ass_id_encrypted' => $ass_id_encrypted);


                    // $myData['slip'][$slipName]['street'][$streetName]['house']['id']= $houseID;
                    // $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['status']= $assHouseStatus;
                    // $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['number']= $houseNumber;

                    // $myData[] = array(
                    //   'id' => $slipId,
                    //   'name' => $slipName,
                    //   'street' => array(
                    //     'name' => $streetName,
                    //     'house' => array(
                    //       'id' => $houseID,
                    //       'details' => array(
                    //         'status' => $houseStatus,
                    //         'number' => $houseNumber
                    //         )
                    //       )
                    //     )
                    //   );

                  // array_push($myData, $myHouse);
                  }
              }
         }

         $myData=collect( $myData['slip'] )->paginate( 3 );

         //dd($myData);
         return view('frontend.my-maps.show', compact('myMap','myData', 'ass_id', 'ass_id_encrypted'));
      } else {
        return view('frontend.my-maps.show_error');
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

        return view('my-maps.edit', compact('map'));
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

        return redirect('my-maps');
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


        return view('my-maps.index', compact('maps'));
    }


    public function unavailable()
    {
        //
        $maps = Map::unavailable()->get();

        //return compact('maps');

        return view('my-maps.index', compact('maps'));
    }

    public function houseStatus(AssignmentHouseRequest $request)
    {
       //dd("I'm in!");

       if(Request::ajax()) {
        $id = $request->input('id');
        $status = $request->input('status');
        $map_id = $request->input('map_id');
                $ass_id = $request->input('ass_id');


        if($status==="true"){
          $status=1;
        } elseif($status==="false") {
          $status=0;
        }

        /*
        * We need to find the assignment ID so that we know which house on the table assignments_houses to update
        * because we have different records for the same house ID (we can have some from closed assignments)
        */


        //$ass_id = Assignment::where('user_id', Auth::user()->id)->where('map_id', $map_id)->where('finished_on', NULL)->value('id');

        //dd($ass_id);
        //dd("ID: ".$id."  Status: ".$status."  Ass ID:". $ass_id ."User ID: ".Auth::user()->id);

        AssignmentHouse::where('house_id', $id)
                        ->where('assignment_id', $ass_id)
                        ->update(['status' => $status]);


       }

    }

     public function share(AssignmentSlipRequest $request)
    {
              //dd("I'm in");

        if(Request::ajax()) {
        $slipID = Request('slip_id');
        $assID = Request('assignment_id');
        $shared = Request('shared');



        if($shared==="true"){
          $shared=1;
        } elseif($shared==="false") {
          $shared=0;
        }

        //dd($assID);

        /* Check if record for this assignment already exists */
        $Assignment = AssignmentSlip::where('assignment_id', $assID)
                                      ->where('slip_id', $slipID)
                                      ->first();
        if ($Assignment === null) {
          /* if it doesn't exist create a new one */
          AssignmentSlip::create(['assignment_id' => $assID, 'slip_id' => $slipID, 'shared' => $shared]);
          dd('done');
        } else{
          /* if it already exists update the existing one */
          AssignmentSlip::where('assignment_id', $assID)
                      ->where('slip_id', $slipID)
                      ->update(['shared' => $shared]);
        }

      /* Finished checking if record exists*/



        }
    }


    public function showSlip(Slip $request){

      $slips = Slips::all();

      $slip = $request->find($id);
      $mySlip['name'] = $slip->name;

      $streets = Street::all();

      /* Group our collection by Street ID */
      $assignedSlips = $slip->houses->groupBy('slip_id');
      //dd($assignedSlips);
      if (!$assignedSlips->isEmpty()) {


          $data = array();
        foreach ($assignedSlips as $slip) {
          $uniqueSlip = $slip->unique('slip_id')->all();
          $uniqueSlipId = $uniqueSlip[0]['slip_id'];
          $slipName = $slips->find($uniqueSlipId)->name;
          $slipId = $slips->find($uniqueSlipId)->id;

          // $mySlip['slip'][''] => $slipName, 'id' => $slipId];
         // dd($details);
          //$slipDetails['name']

          $assignedHouses = $map->houses->where('slip_id', $uniqueSlipId)->groupBy('street_id');

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

                //  $ass_id_encrypt = Crypt::encryptString($ass_id);

                  /*
                  * Using the assignment ID we just collected we can searh in the table assignments_houses for the correct entry for this
                  * house, that way we will be displaying the correct status
                  */
                  $houseStatus = AssignmentHouse::where('house_id', $houseID)
                                                ->where('assignment_id', $ass_id)
                                                ->value('status');


                    $myData['slip'][$slipName]['id']=$slipId;
                    $myData['slip'][$slipName]['street'][$streetName]['house']['id']= $houseID;
                    $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['status']= $houseStatus;
                    $myData['slip'][$slipName]['street'][$streetName]['house'][$houseID]['number']= $houseNumber;



                  }
              }
         }
         dd($myData);
         return view('frontend.my-slips.show', compact('myMap','myData'));
      } else {
        return view('frontend.my-slips.show_error');
      }

    }




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
