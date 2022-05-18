<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\BuyOrders;
class BuyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      //\App\BuyOrders::check();
      if (Auth::id() == 5 && count(BuyOrders::all()) == 0){
        $itemTypes = \App\ItemTypes::orderBy('name')->get();
        foreach($itemTypes as $itemType){
          $buyOrder = new BuyOrders;
          $buyOrder->itemTypeID = $itemType->id;
          $buyOrder->save();
        }
      }
      echo json_encode(\App\BuyOrders::fetch($request->sort));


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
        $buyOrder = \App\BuyOrders::find( $request->buyOrderID);
        $itemType = \App\ItemTypes::find($buyOrder->itemTypeID);
        $item = \App\Items::where('itemTypeID', $buyOrder->itemTypeID)->where('userID', Auth::id())->first();

        if ($item->quantity < $buyOrder->quantity){
          echo json_encode(['error' => "You have less than the required quantity of " . $itemType->name]);
          return;
        }
        $item->quantity -= $buyOrder->quantity;
        $user = Auth::user();
        $user->clacks += $buyOrder->cost;
        $user->save();
        $item->save();
        $buyOrder->active = false;
        $buyOrder->filled_at = now();
        $buyOrder->filledBy = Auth::id();
        $buyOrder->save();

        $newBuyOrder = new \App\BuyOrders;
        $newBuyOrder->quantity = $buyOrder->quantity * 2;
        $newBuyOrder->cost = $buyOrder->cost * 1.75;
        $newBuyOrder->unitCost = round($newBuyOrder->cost / $newBuyOrder->quantity);
        $newBuyOrder->itemTypeID = $buyOrder->itemTypeID;
        $newBuyOrder->save();

        $chat = new \App\Chat;
        $chat->message = $user->name . ' sold ' . $buyOrder->quantity
          . $itemType->name . " to The State for " . $buyOrder->cost . " clacks.";
        $chat->save();

        $status = 'You sold ' . $buyOrder->quantity . " "
          . $itemType->name . " to The State for " . $buyOrder->cost . " clacks.";
        \App\History::new(Auth::id(), 'state', $status);
        echo json_encode([
          'status'  => $status,
          'info'       => \App\User::fetchInfo(),
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
