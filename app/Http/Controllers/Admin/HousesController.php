<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\House;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use App\Http\Requests\HouseRequest;

class HousesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $houses = House::paginate(15);
        // dd($houses);
        return view('admin.houses.index', compact('houses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
         $slips = \DB::table('slips')->lists('name', 'id');
      $streets = \DB::table('streets')->lists('name', 'id');


      return view('admin.houses.create', compact('slips', 'streets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(HouseRequest $request)
    {
        
        House::create($request->all());

        Session::flash('flash_message', 'House added!');
        Session::flash('message_type', 'alert-success');

        return redirect('admin/houses');
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
        $house = House::findOrFail($id);

        return view('admin.houses.show', compact('house'));
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
        $house = House::findOrFail($id);

        return view('admin.houses.edit', compact('house'));
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
        
        $house = House::findOrFail($id);
        $house->update($request->all());

        Session::flash('flash_message', 'House updated!');
        Session::flash('message_type', 'alert-success');

        return redirect('admin/houses');
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
        House::destroy($id);

        Session::flash('flash_message', 'House deleted!');
        Session::flash('message_type', 'alert-success');

        return redirect('admin/houses');
    }

}
