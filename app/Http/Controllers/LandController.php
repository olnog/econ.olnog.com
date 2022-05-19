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
      $filter  = $request->filter;
      $sort = $request->sort;
      $landTypes = \App\Land::fetchLandTypes();
      if ($filter == null && $landType == null){
        $filter = 'mine';
        $landType = 'all';
        $sort = 'id';
      } else if ($filter == 'all' && $landType == 'all'){
        $landType = $landTypes[rand(0, count($landTypes) -1)];
      }

      if ($filter == 'mine' ){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->where('userID', \Auth::id())->orderBy($sort)->get();
        if ($landType != 'all'){
          $land = Land::join('users', 'land.userID', 'users.id')
            ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
            'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
            'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
            ->where('type', $landType)->where('userID', \Auth::id())
            ->orderBy($sort)->get();
        }
      } else if ($filter == 'hostile'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->whereNotNull('hostileTakeoverBy')->orderBy($sort)->get();
        if ($landType != 'all'){
          $land = Land::join('users', 'land.userID', 'users.id')
            ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
            'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
            'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
            ->where('type', $landType)->whereNotNull('hostileTakeoverBy')
            ->orderBy($sort)->get();
        }
      } else if ($filter == 'all'){
        $land = Land::join('users', 'land.userID', 'users.id')
          ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
          'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
          'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
          ->orderBy($sort)->get();
        if ($landType != 'all'){
          $land = Land::join('users', 'land.userID', 'users.id')
            ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
            'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
            'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')
            ->where('type', $landType)->orderBy($sort)->get();
        }
      }


      return view('Land.index')->with([
        'filter'    => $filter,
        'land'      => $land,
        'landType'  => $landType,
        'sort'      => $request->sort,
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
          'info'  => \App\User::fetchInfo(),
          'status' => "<span class='actionInput'>Clacks: <span class='fn'>-"
            . $request->amount . "</span> [" . number_format($user->clacks)
            . "] </span> &rarr; Parcel #" . $land->id
            . " - Bribe: <span class='fp'>+" . $request->amount . "</span> ["
            . number_format($land->bribe) ."] Valuation: <span class='fp'>+"
            . $request->amount . "</span> [" . number_format($land->valuation)
            . "]",
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
