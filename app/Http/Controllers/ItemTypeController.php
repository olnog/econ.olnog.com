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
        $durabilities = ["horrible", "poor", "average", "good", "great"];
        foreach($materials as $material){
          foreach($durabilities as $durability){
            $itemTypes = new ItemTypes;
            $itemTypes->name = $request->name;
            $itemTypes->countable = $request->countable == 'on';
            $itemTypes->description = $request->description;
            $itemTypes->durability = $durability;
            $itemTypes->material = $material;
            $itemTypes->save();
            \App\ItemTypes::new($itemTypes->id);
          }
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
        //
    }
}
