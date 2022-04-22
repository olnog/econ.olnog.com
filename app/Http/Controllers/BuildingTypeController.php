<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use \App\BuildingTypes;

class BuildingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if (Auth::id() == 5){
        return view('BuildingTypes/index')->with('buildingTypes', BuildingTypes::orderBy('name', 'asc')->get());
      }
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
      $buildingType = new BuildingTypes;
      $buildingType->name = $request->name;
      $buildingType->farming = $request->farming == 'on';
      $buildingType->description = $request->description;
      $buildingType->skill = $request->skill;
      $buildingType->cost = $request->cost;
      $buildingType->actions = $request->actions;
      $buildingType->save();
      return redirect()->route('buildingTypes.index');
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
      if ( Auth::id() != 5){
        echo "FALSE";
        return;
      }
      $buildingType = \App\BuildingTypes::find($id);
      $buildingType->name = $request->name;
      $buildingType->description = $request->description;
      $buildingType->skill = $request->skill;
      $buildingType->cost = $request->cost;
      $buildingType->actions = $request->actions;
      $buildingType->save();
      return redirect()->route('buildingTypes.index');

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
