<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Land;
class LandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $landType = $request->landType;
      if ($request->sort == 'valuation'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->where('type', $landType)->orderBy('valuation')->get();
      } else if ($request->sort == 'name'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->where('type', $landType)->orderBy('name')->get();
      } else if ($request->sort == 'type'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->where('type', $landType)->orderBy('type')->get();
      } else {
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->where('type', $landType)->get();
      }

      return view('Land.index')->with([
        'land'      => $land,
        'landType'  => $landType,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      if ($request->sort == 'valuation'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->orderBy('valuation')->get();
      } else if ($request->sort == 'name'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->orderBy('name')->get();
      } else if ($request->sort == 'type'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->orderBy('type')->get();
      } else {
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')->get();
      }
      return view('land2')->with([
        'land' => $land,
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
      $land = \App\Land::find($id);
      if ($land->hostileTakeoverBy == null){
        echo "There's no hostile take over right now.";
        return;
      }

      if (\Auth::id() == $land->userID
        || \Auth::id() == $land->hostileTakeoverBy){
        return view('hostileTakeover')->with([
          'owner' => \App\User::find($land->userID),
          'attacker' => \App\User::find($land->hostileTakeoverBy),
          'ownerBids' => \App\Bids::where('landID', $land->id)
            ->where('userID', $land->userID)
            ->where('hostileTakeoverNum', $land->hostileTakeoverNum)
            ->get(),

          'counterBids' => \App\Bids::where('landID', $land->id)
            ->where('userID', $land->hostileTakeoverBy)
            ->where('hostileTakeoverNum', $land->hostileTakeoverNum)
            ->get(),
          'land' => $land,

          'lastBid' => \App\Bids::lastBid($land->id),
        ]);
      }
      echo "You aren't allowed to view this page.";
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
        $land = \App\Land::find($id);
        $user = \App\User::find(\Auth::id());
        if ($land->userID != \Auth::id()){
          echo json_encode(['error' => "You don't own this property. Chill."]);
          return;
        } else if ($user->clacks < $request->amount){
          echo json_encode(['error' => "You don't have enough clacks to do this. "]);
          return;
        }
        $user->clacks -= $request->amount;
        $user->save();
        $land->valuation += $request->amount;
        $land->bribe += $request->amount;
        $land->save();
        echo json_encode([
          'avgBribe' => \App\Land::averageBribe(),
          'clacks' => $user->clacks,
          'land' => \App\Land::fetch(),
          'status' => "You put " . $request->amount . " clacks up for the bribe on parcel #" . $land->id,
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
