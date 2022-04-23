<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use \App\Equipment;
use \App\SkillTypes;
use \App\Skills;
use \App\Items;
use \App\ItemTypes;
use \App\Labor;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      echo json_encode(\App\Actions::available(\Auth::id()));
      $labor = \App\Labor::fetch();

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
      \App\Metric::logAllButtons(\Auth::id(), $request->buttons);
      $actionName = $request->name;
      if ($request->automation  == 'true'){
        $food = \App\Items::fetchByName('Food', Auth::id());
        if ($food->quantity == 0){
          echo json_encode(['error' => "You're automating actions but you don't have any more food." ]);
          return;
        }
        $food->quantity--;
        $food->save();
      }
      $msg = \App\Actions::do($actionName, Auth::id(), Auth::id(), null);
      $status = "";
      if (isset($msg['error'])){
        echo json_encode(['error' => $msg['error'] ]);
        \App\History::new(Auth::id(), 'action', 'ERROR! : ' . $msg['error']);
        return;
      } else {
        $status = $msg['status'];
        \App\History::new(Auth::id(), 'action', $status);
      }
      $user = \App\User::find(\Auth::id());
      echo json_encode([
        'actions' => \App\Actions::fetch(\Auth::id()),
        'buildingSlots' => $user->buildingSlots,
        'buildings' => \App\Buildings::fetch(),
        'clacks' => $user->clacks,
        'labor' => \App\Labor::fetch(),
        'equipment' => \App\Equipment::fetch(),
        'history' => \App\History::fetch(),
        'csrf' => csrf_token(),
        'status' => $status,
        'items' => Items::fetch(),
        'land' => \App\Land::fetch(),
        'numOfItems' => \App\Items::fetchTotalQuantity(Auth::id()),
        'itemCapacity' => $user->itemCapacity,
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
        //
    }
}
