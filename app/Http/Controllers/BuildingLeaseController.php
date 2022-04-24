<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuildingLeaseController extends Controller
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
        $contract = \App\Contracts::find($request->contractID);
        \App\BuildingLease::new($request->contractID, $contract->buildingID);
        echo json_encode([
          'actions'         =>  \App\Actions::fetch(\Auth::id()),
          'buildingLeases'  =>  \App\BuildingLease::fetch(),
          'contracts'       =>  \App\Contracts::fetch(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
      $lease = \App\BuildingLease::where('active', 1)
        ->where('userID', \Auth::id())->where('contractID', $id)->first();
      $lease->active = 0;
      $lease->save();
      echo json_encode([
        'actions'         =>  \App\Actions::fetch(\Auth::id()),
        'buildingLeases'  =>  \App\BuildingLease::fetch(),
        'history'         =>  \App\History::fetch(),
        'contracts'       =>  \App\Contracts::fetch(),
        'status'          => 'You canceled this building lease.',
      ]);
    }
}
