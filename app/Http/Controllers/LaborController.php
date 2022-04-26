<?php

namespace App\Http\Controllers;
use App\Labor;
use App\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaborController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $labor = \App\Labor::where('userID', Auth::id())->first();
      $user = Auth::user();
      echo json_encode([
        'labor' => \App\Labor::fetch(),
        //'availableSkillPoints' => $labor->availableSkillPoints,
        'clacks' => $user->clacks,
        //'equipped' => $labor->equipped,
        //'workHours' => $labor->workHours,
        'itemCapacity' => $user->itemCapacity,
        'buildingSlots' => $user->buildingSlots,
        'numOfItems' => \App\Items::fetchTotalQuantity(Auth::id()),
        'userID' => Auth::id(),
        'username' => Auth::user()->name,
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
      $usesArr = ['stone' => 100, 'iron'=> 1000, 'steel'=> 10000];
      $toolItem = Items::where('items.id', $request->itemID)
        ->join('itemTypes', 'items.itemTypeID', 'itemTypes.id')
        ->select('items.id', 'itemTypeID', 'quantity', 'name', 'description',
          'durability', 'material')->first();
      if ($toolItem->material != null){
        $numOfUses = $usesArr[$toolItem->material];
      }
      if ($toolItem->quantity < 1){
        return;
      }
      $toolItem->quantity--;
      $toolItem->save();
      $equipment = new \App\Equipment;
      $equipment->itemTypeID = $toolItem->itemTypeID;
      $equipment->userID = Auth::id();


      if ($toolItem['name'] == 'Radiation Suit'
        || substr($toolItem['name'], 0, strlen('Chainsaw'))   == 'Chainsaw'
        || substr($toolItem['name'], 0, strlen('Jackhammer')) == 'Jackhammer'
        || substr($toolItem['name'], 0, strlen('Car'))        == 'Car'
        || substr($toolItem['name'], 0, strlen('Tractor'))    == 'Tractor'
        || substr($toolItem['name'], 0, strlen('Bulldozer'))  == 'Bulldozer'
      ){
        $numOfUses = 1000;
      }
      $equipment->uses = $numOfUses;
      $equipment->totalUses = $numOfUses;
      $equipment->save();
      $labor = \App\Labor::fetch();
      if ($toolItem['name'] == 'Radiation Suit'){
        $labor->alsoEquipped = $equipment->id;
      } else {
        $labor->equipped = $equipment->id;
      }

      $labor->save();
      echo json_encode([
        'actions' => \App\Actions::fetch(\Auth::id()),
        'labor' => \App\Labor::fetch(),
        'items' => Items::fetch(),
        'equipment' => \App\Equipment::fetch()
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
