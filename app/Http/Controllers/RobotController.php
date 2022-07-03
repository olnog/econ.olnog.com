<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RobotController extends Controller
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
      $robot = \App\Items::fetchByName('Robots', \Auth::id());
      $bannedActions = \App\Actions::fetchBanned();
      $action = \App\Actions::fetchByName(\Auth::id(), $request->actionName);
      if ($robot->quantity < 1){
        echo json_encode(['error' => "You don't have any Robots."]);
        return;
      } else if ($action == null || !$action->unlocked || $action->rank == 0){
        echo json_encode(['error' => "This is not a valid action."]);
        return;
      } else if (in_array($request->actionName, $bannedActions)){
        echo json_encode(['error' => "You can't program this Robot with "
          . $request->actionName . ". Sorry."]);
        return;
      }
      $status = "You programmed a Robot with the " . $request->actionName . " skill.";
      $robot->quantity--;
      $robot->save();
      \App\Robot::new($action->actionTypeID, \Auth::id());
      echo json_encode([
        'robots' => \App\Robot::fetch(),
        'status' => $status,
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
      $bannedActions = \App\Actions::fetchBanned();
      $action = \App\Actions::fetchByName(\Auth::id(), $request->actionName);
      if ($request->actionName == 'null'){
        echo json_encode(['error' => "You haven't setup which action you want to program."]);
        return;
      } else if ($action == null || !$action->unlocked || $action->rank == 0){
        echo json_encode(['error' => "This is not a valid action."]);
        return;
      } else if (in_array($request->actionName, $bannedActions)){
        echo json_encode(['error' => "You can't program this Robot with "
          . $request->actionName . ". Sorry."]);
        return;
      }

      $robot = \App\Robot::find($id);
      $status = "You reprogrammed a Robot with the " . $request->actionName . ".";
      $robot->actionTypeID = $action->actionTypeID;
      $robot->save();
      echo json_encode([
        'actions' => \App\Actions::fetch(\Auth::id()),
        'robots' => \App\Robot::fetch(),
        'status' => $status,
      ]);
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
