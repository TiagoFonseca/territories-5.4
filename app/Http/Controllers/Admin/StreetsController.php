<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\House;
use App\Street;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class StreetsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $streets = Street::paginate(15);

        return view('admin.streets.index', compact('streets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.streets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Street::create($request->all());

        Session::flash('flash_message', 'Street added!');

        return redirect('admin/streets');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show(Street $request, $id)
    {
       
        $street = Street::find($id);
        $houses = $street->houses;
        if($houses->isEmpty()){
            Session::flash('flash_message', 'There are no houses assigned to this street!');
            Session::flash('flash_type', 'alert-error');
            return redirect('admin/streets');
         
         }else{
         // dd($myData);
      return view('admin.streets.show', compact('street'));
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
        $street = Street::findOrFail($id);

        return view('admin.streets.edit', compact('street'));
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
        
        $street = Street::findOrFail($id);
        $street->update($request->all());

        Session::flash('flash_message', 'Street updated!');

        return redirect('admin/streets');
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
        Street::destroy($id);

        Session::flash('flash_message', 'Street deleted!');

        return redirect('admin/streets');
    }

}
