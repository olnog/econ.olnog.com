<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //var_dump(\App\Buildings::destroy(46));
      echo json_encode(\App\Buildings::fetch());
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
      $user = Auth::user();

      $msg = \App\Buildings::build($request->buildingName, Auth::id(), Auth::id());
      if (isset($msg['error'])){
        echo json_encode(['error' => $msg['error']]);
        \App\History::new(Auth::id(), 'buildings', $msg['error']);
        return;
      } else {
        $status = $msg['status'];
        \App\History::new(Auth::id(), 'buildings', $msg['status']);
      }
      echo json_encode([
        'actions' => \App\Actions::available(),
        'buildings' => \App\Buildings::fetch(),
        'history' => \App\History::fetch(),
        'items' => \App\Items :: fetch(),
        'labor' => \App\Labor::fetch(),
        'status' => $status,
        'buildingSlots' =>  \App\User::find(Auth::id())->buildingSlots,
        'itemCapacity' => \App\User::find(Auth::id())->itemCapacity,
        'numOfItems' => \App\Items::fetchTotalQuantity(Auth::id()),
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
      if ($request->action == 'repair'){
        $msg = \App\Buildings::repair($id, Auth::id(), Auth::id());
      } else if ($request->action == 'rebuild'){
        $msg = \App\Buildings::rebuild($id, Auth::id(), Auth::id());
      }
      if (isset($msg['error'])){
        echo json_encode(['error' => $msg['error']]);
        \App\History::new(Auth::id(), 'buildings', $msg['error']);
        return;
      } else {
        $status = $msg['status'];
        \App\History::new(Auth::id(), 'buildings', $msg['status']);
      }
      echo json_encode([
        'actions' => \App\Actions::available(),
        'buildings' => \App\Buildings::fetch(),
        'history' => \App\History::fetch(),
        'items' => \App\Items::fetch(),
        'status' => $status,
        'numOfItems' => \App\Items::fetchTotalQuantity(Auth::id()),
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
      \App\Buildings::destroyBuilding($id);
      echo json_encode([
        'actions' => \App\Actions::available(),
        'buildings' => \App\Buildings::fetch(),
        'itemCapacity' => \App\User::find(Auth::id()),
        'numOfItems' => \App\Items::fetchTotalQuantity(Auth::id()),
      ]);
    }
}
