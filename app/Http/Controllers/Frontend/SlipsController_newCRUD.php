<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Slip;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class SlipsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $slips = Slip::paginate(15);

        return view('admin.slips.index', compact('slips'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
         $maps = \DB::table('maps')->lists('name', 'id');

         //$slips = Slip::all();
         return view('admin.slips.create', compact('maps'));
        //return view('admin.slips.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', ]);

        Slip::create($request->all());

        Session::flash('flash_message', 'Slip added!');

        return redirect('admin/slips');
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
        $slip = Slip::findOrFail($id);

        return view('admin.slips.show', compact('slip'));
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
        $slip = Slip::findOrFail($id);

        return view('admin.slips.edit', compact('slip'));
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
        $this->validate($request, ['name' => 'required', ]);

        $slip = Slip::findOrFail($id);
        $slip->update($request->all());

        Session::flash('flash_message', 'Slip updated!');

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
