<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\SkillTypes;
class SkillTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::id() == 5){
          return view('SkillTypes/index')->with('skillTypes', SkillTypes::orderBy('name', 'asc')->get());
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
        $skillTypes = new SkillTypes;
        $skillTypes->name = $request->name;
        $skillTypes->description = $request->description;
        $skillTypes->identifier = $request->identifier;
        $skillTypes->buildings = $request->buildings;
        $skillTypes->equipment = $request->equipment;
        $skillTypes->save();
        $users = \App\User::all();
        foreach ($users as $user){
          $skill = new \App\Skills;
          $skill->skillTypeID = $skillTypes->id;
          $skill->userID = $user->id;
          $skill->save();
        }
        return redirect()->route('skillTypes.index');
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
      $skillType = SkillTypes::find($id);
      $skillType->name = $request->name;
      $skillType->description = $request->description;
      $skillType->buildings = $request->buildings;
      $skillType->equipment = $request->equipment;
      $skillType->identifier = $request->identifier;
      $skillType->save();
      return redirect()->route('skillTypes.index');

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
