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

Route::get('/test', function(){

  var_dump(\App\Buildings::howManyBuildingsAndFieldsDoTheyHave(5)
  < \App\User::find(Auth::id())->buildingSlots,
  \App\Land::doTheyHaveAccessTo('jungle'),
  \App\Buildings::howManyFieldsForThisLandType('jungle', 5),
   \App\Buildings::howManyFieldsCanTheyHave('jungle', 5));
});

Route::get('/stop', function(){
  $user = \App\User::find(\Auth::id());
  $user->action = null;
  $user->save();
});

Route::post('/metric', function (Request $request){
  \App\Metric::logAllButtons(\Auth::id(), $request->buttons);
});


Route::get('/read', function(){
  $books = \App\Items::fetchByName('Books', \Auth::id());
  $labor = \App\Labor::fetch();
  $requiredBooks = ($labor->availableSkillPoints + $labor->allocatedSkillPoints);
  if ($books->quantity < $requiredBooks){
    echo json_encode(['error' => "You don't have enough Books (" . $requiredBooks
      . ")to do this now. Sorry."]);
    return;
  }
  $books->quantity -= $requiredBooks;
  $books->save();
  $labor->availableSkillPoints++;
  $labor->save();
  echo json_encode([
    'items'   => \App\Items::fetch(),
    'labor'   => \App\Labor::fetch(),
    'status'  => "You used " . $requiredBooks
    . " Books to give yourself a new point. You can now unlock a new action. ",
  ]);
});


Route::get('/report', function(){
  $reports = \App\Report:: all();
  foreach ($reports as $report){
    echo $report->report . "<BR>";
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


Route::get('/changes', function(){
  return view('changes');
});

Route::get('/help', function(){
  return view('help')->with([
    'actionTypes'   => \App\ActionTypes::orderBy('name')->get(),
    'itemTypes' => \App\ItemTypes::orderBy('name')->get(),
  ]);
});

Route::get('/buildingCosts', function(){
  return view('buildingCosts')->with([
    'buildingSlots' => \App\User::find(Auth::id())->buildingSlots,
    'buildingTypes' => \App\BuildingTypes::orderBy('name', 'asc')->get(),
    'build' => \App\Actions::fetchByName(Auth::id(), 'build'),
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
      'info'    => \App\User::fetchInfo(),
      'robots'  => \App\Robot::where('userID', \Auth::id())->select('id')->get(),
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
    'children' => \App\Items::fetchByName('Children', \Auth::id())->quantity,
    'clones' => \App\Items::fetchByName('Clones', \Auth::id())->quantity,
    'labor' => $labor,
    'tax' => \App\Labor::fetchTax(),
  ]);
})->name('rebirth');

Route::get('/reborn', function(Request $request){
  $labor = \App\Labor::fetch();
  if ($labor->lastRebirth != null && (strtotime('now') - strtotime($labor->lastRebirth)) / 3600 < 1){
    echo "<div><a href='/' style='text-align:center;'>go back</a></div><div>You need to wait at least an hour until you can Rebirth. You have "
      . round( 60 - ((strtotime('now') - strtotime($labor->lastRebirth)) / 60))
      . " minutes to wait.</div>";
    return;
  }
  $labor->rebirth = true;
  $labor->lastRebirth = date('Y-m-d H:i:s');
  $labor->save();
  return redirect()->route('rebirth');

});

Route::post('/rebirth', function(Request $request){
  $labor = \App\Labor::fetch();
  if (!$labor->rebirth ){
    return redirect()->route('home');
  }
  \App\Labor::rebirth($request->legacy == "on", $request->immortality == "on");
  return redirect()->route('home');
});

Route::post('/reset', function(){
  //\App\User::reset();

  $labor = \App\Labor::fetch();
  //$labor->rebirth = true;
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
  'feedback'        => 'FeedbackController',

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
