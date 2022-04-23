<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/report', function(){
  $reports = \App\Report:: all();
  foreach ($reports as $report){
    echo $report->report;
  }

});

Route::post('/settings', function (Request $request){
  $user = \App\User::find(\Auth::id());
  $user->soundSetting = $request->soundSetting == 'true';
  $user->eatFoodSetting = $request->eatFoodSetting == 'true';
  $user->useHerbMedsSetting = $request->useHerbMedsSetting == 'true';
  $user->useBioMedsSetting = $request->useBioMedsSetting == 'true';
  $user->useNanoMedsSetting = $request->useNanoMedsSetting == 'true';
  $user->save();
});

Route::get('/account', function(){
  return view('account')->with([
    'user' => \App\User::find(\Auth::id()),
    'history' => \App\History::fetch(),
  ]);
});
Route::post('/robotActions', function (Request $request){
  \App\Robot::processActions(json_decode($request->robotData));

});
Route::post('/autobribe', function (Request $request){
  if (!filter_var($request->amount, FILTER_VALIDATE_INT)){
    return;
  }
  $user = \App\User::find(\Auth::id());
  $user->autoBribe = $request->amount;
  $user->save();
  echo json_encode([
    'land' => \App\Land::fetch(),
    'autoBribe' => $user->autoBribe,
  ]);
});
Route::get('/test', function(){
  $items = \App\Items::all();
  $badItemTypes = [];
  foreach($items as $item){
    $itemType = \App\ItemTypes::find($item->itemTypeID);
    if ($itemType == null && !in_array($item->itemTypeID, $badItemTypes)){
      $badItemTypes [] = $item->itemTypeID;
      echo $item->itemTypeID . "<br>";
    }
  }
  //var_dump($badItemTypes);
});

Route::get('/changes', function(){
  return view('changes');
});

Route::get('/help', function(){
  return view('help')->with([
    'itemTypes' => \App\ItemTypes::orderBy('name')->get()
  ]);
});

Route::get('/buildingCosts', function(){
  return view('buildingCosts')->with([
    'buildingSlots' => \App\User::find(Auth::id())->buildingSlots,
    'buildingTypes' => \App\BuildingTypes::orderBy('name', 'asc')->get(),
    'construction' => \App\Skills::fetchByIdentifier('construction', Auth::id()),
    'land' => \App\Land::where('userID', Auth::id())->get(),


  ]);
});

Route::post('/bribe', function(Request $request){
  $msg = \App\Land::payAllBribes($request->amount);
  if (isset($msg['error'])){
    echo json_encode(['error' => $msg['error']]);
    return;
  } else {
    $status = $msg['status'];
  }
  echo json_encode([
    'avgBribe' => \App\Land::averageBribe(),
    'clacks' => \App\User::find(Auth::id())->clacks,
    'land' => \App\Land::fetch(),
    'status' => $status,
  ]);
});

Route::get('/ajax', function () {
  if (Auth::check()){
    $user = \App\User::find(Auth::id());
    echo json_encode([
      'actions'       => \App\Actions::fetch(\Auth::id()),
      'autoBribe'     => $user->autoBribe,
      'avgBribe'      => \App\Land::averageBribe(),
      'buildingLeases'=> \App\BuildingLease::fetch(),
      'buildings'     => \App\Buildings::fetch(),
      'buildingSlots' => $user->buildingSlots,
      'buyOrders'     => \App\BuyOrders::fetch(null),
      'clacks'        => $user->clacks,
      'contracts'     => \App\Contracts::fetch(),
      'equipment'     => \App\Equipment::fetch(),
      'hostileTakeover' => \App\Land::isThereAHostileTakeover(),
      'labor'         => \App\Labor::fetch(),
      'leases'        => \App\Lease::fetch(),
      'itemCapacity'  => $user->itemCapacity,
      'items'         => \App\Items::fetch(),
      'itemTypes'     => \App\ItemTypes::all(),
      'land'          => \App\Land::fetch(),
      'numOfItems'    => \App\Items::fetchTotalQuantity(Auth::id()),
      'robots'        => \App\Robot::fetch(),
      'settings'      => [
        'sound'=>$user->soundSetting,
        'eatFood'=>$user->eatFoodSetting,
        'useHerbMeds' => $user->useHerbMedsSetting,
        'useBioMeds' => $user->useBioMedsSetting,
        'useNanoMeds' => $user->useNanoMedsSetting,
      ],
      'skills'        => \App\Skills::fetch(),
      'statusHistory' => \App\History::fetch(),
      'userID'        => $user->id,
      'username'      => $user->name,
    ]);
  }
});

Route::get('/old', function(){
  return vieW('old');
});

Route::get('/', function () {
  if (Auth::user() != null){
    return redirect()->route('home');
  }
    return view('welcome');
});

Route::get('/rebirth', function () {
  $labor = \App\Labor::fetch();
  if (!$labor->rebirth ){
    return redirect()->route('home');
  }

  return view('rebirth')->with([
    'books' => \App\Items::fetchByName('Books', \Auth::id())->quantity,
    'children' => \App\Items::fetchByName('Children', \Auth::id())->quantity,
    'labor' => $labor,
    'skills'=>\App\Skills::where('userID', Auth::id())->where('rank', '>', 0)
      ->join("skillTypes", 'skills.skillTypeID', 'skillTypes.id')->get(),
    'tax' => \App\Labor::fetchTax(),
  ]);
})->name('rebirth');

Route::post('/rebirth', function(Request $request){
  $labor = \App\Labor::fetch();
  if (!$labor->rebirth ){
    return redirect()->route('home');
  }
  if ($request->legacy == "on" && $request->legacySkillTypeID == null){
    echo "You have to select a skill if you want to do the Legacy perk.";
    return;
  }
  \App\Labor::rebirth( $request->genius == "on", $request->legacy == "on", $request->legacySkillTypeID, $request->childProdigy == "on");

  return redirect()->route('home');

});

Route::post('/reset', function(){
  //\App\User::reset();

  $labor = \App\Labor::fetch();
  $labor->rebirth = true;
  $labor->save();

  return redirect()->route('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resources([
  'actions'         => 'ActionController',
  'actionTypes'     => 'ActionTypeController',
  'bids'            => 'BidController',
  'buildingLease'   => 'BuildingLeaseController',
  'chat'            => 'ChatController',
  'contracts'       => 'ContractController',
  'equipment'       => 'EquipmentController',
  'history'         => 'HistoryController',
  'labor'           => 'LaborController',
  'land'            => 'LandController',
  'lease'           => 'LeaseController',
  'skills'          => 'SkillsController',
  'skillTypes'      => 'SkillTypeController',
  'items'           => "ItemController",
  'itemTypes'       => "ItemTypeController",
  'buildings'       => 'BuildingController',
  'buildingTypes'   => 'BuildingTypeController',
  'buyOrders'       => 'BuyOrderController',
  'robots'          => 'RobotController',
]);
