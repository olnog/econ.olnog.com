<?php

namespace App\Http\Controllers;
use \App\ItemTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if (Auth::id() == 5){
        return view('ItemTypes/index')->with('itemTypes', ItemTypes::orderBy('name', 'asc')->get());
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
      if (Auth::id() !=  5 ){
        return;
      }

      if ($request->tool == 'on'){
        $materials = ["iron", "stone", 'steel'];
        foreach($materials as $material){
            $itemTypes = new ItemTypes;
            $itemTypes->name = $request->name . " (" . $material . ")";
            $itemTypes->countable = $request->countable == 'on';
            $itemTypes->description = $request->description;
            $itemTypes->material = $material;
            $itemTypes->save();
            \App\ItemTypes::new($itemTypes->id);
        }
        return redirect()->route('itemTypes.index');
      }
      $itemTypes = new ItemTypes;
      $itemTypes->name = $request->name;
      $itemTypes->countable = $request->countable == 'on';
      $itemTypes->description = $request->description;
      $itemTypes->save();
      \App\ItemTypes::new($itemTypes->id);
      return redirect()->route('itemTypes.index');

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
      $itemType = \App\ItemTypes::find($id);
      $itemType->name = $request->name;
      $itemType->description = $request->description;
      $itemType->save();
      return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::id() != 5){
          return;
        }
        $itemType = \App\ItemTypes::find($id);
        $buyOrders = \App\BuyOrders::where('itemTypeID', $itemType->id)->get();
        $contracts = \App\Contracts::where('itemTypeID', $itemType->id)->get();
        $equipment = \App\Equipment::where('itemTypeID', $itemType->id)->get();
        $items = \App\Items::where('itemTypeID', $itemType->id)->get();
        foreach ($buyOrders as $buyOrder){
          \App\BuyOrders::destroy($buyOrder->id);
        }
        foreach($contracts as $contract){
          \App\Contracts::destroy($contract->id);
        }
        foreach($equipment as $shit){
          \App\Equipment::destroy($shit->id);
        }
        foreach($items as $item){
          \App\Items::destroy($item->id);
        }
        \App\ItemTypes::destroy($itemType->id);
        return redirect()->route('itemTypes.index');

    }
}
