<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use \App\Items;
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $labor = \App\Labor::fetch();
      return view('Items.index')->with([
        'actions'  => \App\Actions::fetchUnlocked(\Auth::id(), true),
        'booksReq'
          => $labor->availableSkillPoints + $labor->allocatedSkillPoints,
        'buyOrderItems'     => \App\Items::fetchItemsForBuyOrders(),
        'buyOrders'       => \App\BuyOrders::fetch(null),
        'clacks'  => \App\User::find(\Auth::id())->clacks,
        'equippableItems' => \App\Items::fetchItemNamesForEquipment(),
        'items'   => \App\Items::fetch(),

      ]);
      /*
      echo json_encode([
        'items' => Items::fetch(),
        'itemTypes' => \App\ItemTypes::all(),
      ]);
      */
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
        //
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
    public function update(Request $request, $id){
      if ($request->what == 'useMeds'){
        $msg = \App\Items::useMeds($id);
      }
      if (isset($msg['error'])){
        return;
      }
      echo json_encode([
        'info'  => \App\User::fetchInfo(),
        'status'=> $msg['status'],
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
      $item = \App\Items::find($id);
      $itemType = \App\ItemTypes::find($item->itemTypeID);
      if ($request->quantity > $item->quantity || $itemType->name == 'Nuclear Waste'){
        return;
      }
      $item->quantity -= $request->quantity;
      $item->save();
      echo json_encode([
        'info'    => \App\User::fetchInfo(),
      ]);
    }
}
