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
      $labor = \App\Labor::fetch();
      $alsoEquipped = null;
      $mainEquipped = null;
      if ($labor != null && $labor->equipped != null){
        $mainEquipped = \App\Equipment::where('equipment.id', $labor->equipped)
          ->join('itemTypes', 'itemTypes.id', 'equipment.itemTypeID')
          ->select('itemTypes.name', 'equipment.id', 'equipment.uses',
            'equipment.totalUses')->first();
      }
      if ($labor != null && $labor->alsoEquipped != null){
        $alsoEquipped = \App\Equipment::where('equipment.id', $labor->alsoEquipped)
          ->join('itemTypes', 'itemTypes.id', 'equipment.itemTypeID')
          ->select('itemTypes.name', 'equipment.id', 'equipment.uses',
            'equipment.totalUses')->first();
      }
      $actionable = \App\Actions::fetchActionable(\Auth::id(), true, null);
      return view('Actions.index')->with([
        'actions'             => \App\Actions::fetchUnlocked(\Auth::id(), true),
        'actionable'          => $actionable,
        'allEquipment'        => \App\Equipment::fetch(),
        'banned'              => \App\Actions::fetchBanned(),
        'buildableBuildings'  => \App\Buildings::fetchBuildingsYouCanBuild(),
        'canTheyBuild'       => \App\Buildings
          ::howManyBuildingsAndFieldsDoTheyHave(\Auth::id())
          < \App\User::find(\Auth::id())->buildingSlots,
        'electricity'         => \App\Items
          ::fetchByName('Electricity', \Auth::id())->quantity,
        'equipped'            => [
                                    'also' => $alsoEquipped,
                                    'main' => $mainEquipped,
                                ],
        'food'               => \App\Items::fetchByName('Food', \Auth::id())->quantity,
        'freelanceContracts'   => \App\Contracts::where('active', 1)
          ->where('category', 'freelance')->orderBy('action')
          ->orderBy('price', 'desc')->get(),
        'hireableContracts'   => \App\Contracts::where('active', 1)
          ->where('userID', '!=', \Auth::id())
          ->where('category', 'hire')
          ->whereIn('action', $actionable)->orderBy('action')
          ->orderBy('price', 'desc')->get(),
        'relevantFuel'        => \App\Equipment::fetchFuel(),
        'repairableBuildings' => \App\Buildings::fetchRepairable(false),
        'skillPointCent'      => $labor->actions / $labor->actionsUntilSkill * 100,
        'robots'              => \App\Robot::fetch(),
      ]);
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

      $msg = \App\Actions::do($request->name, Auth::id(), Auth::id(), null, $request->automation  == 'true', false);
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
      $lastAction = null;
      if ($request->automation  == 'true'){
        $user->action = $request->name;

      } else {
        $user->action = null;
      }
      $user->save();
      if (in_array($request->name, \App\Actions::fetchActionable(\Auth::id(), true, $request->name))){
        $lastAction = $request->name;
      }
      echo json_encode([
        'lastAction' => $lastAction,
        'info'       => \App\User::fetchInfo(),
        'csrf'       => csrf_token(),
        'status'     => $status,
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
