<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('ActionTypes.index')->with([
        'actionTypes' => \App\ActionTypes::orderBy("name")->get(),
        'labor'       => \App\Labor::fetch(),
        'availableActions'  => \App\Actions::available(\Auth::id()),
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ActionTypes.create')->with([
          'actionTypes' => \App\ActionTypes::orderBy("name")->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $actionType = new \App\ActionTypes;
      $actionType->name = $request->actionName;
      $actionType->description = $request->actionDescription;
      $actionType->save();
      $users = \App\User::all();
      foreach ($users as $user){
        $action = new \App\Actions;
        $action->userID = $user->id;
        $action->actionTypeID = $actionType->id;
        $action->save();
      }
      return redirect()->route('actionTypes.create');

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
      if ($request->whatWeDoing == 'update'){
        $actionType = \App\ActionTypes::find($id);
        if ($request->actionDescription == null){
          $actionType->description = "";
        } else {
          $actionType->description = trim($request->actionDescription);
        }
        $actionType->name = trim($request->actionName);
        $actionType->save();
        return redirect()->route('actionTypes.create');
      }

      $actionType = \App\ActionTypes::find($id);
      $action = \App\Actions::where('userID', \Auth::id())
        ->where('actionTypeID', $actionType->id)->first();
      $labor = \App\Labor::fetch();
      if (!$action->unlocked && $labor->availableSkillPoints > 0 ){
        $labor->availableSkillPoints--;
        $labor->save();
        $action->unlocked = true;
        $action->save();
      }
      return redirect()->route('actionTypes.index');

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
