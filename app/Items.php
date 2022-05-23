<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Items extends Model
{
  protected $table = 'items';

  static public function doTheyHaveEnoughFor($actionName){
    $items = \App\Items::fetchActionItemInput($actionName);
    if ($items === null){
      return true;
    }
    foreach($items as $itemName => $quantity){
      if (!\App\Items::doTheyHave($itemName, $quantity)){
        return false;
      }
    }
    return true;
  }


  static public function fetch(){
    return Items::where('userID', Auth::id())
      ->join('itemTypes', 'items.itemTypeID', 'itemTypes.id')
      ->select('items.id', 'itemTypeID', 'quantity', 'name', 'description',
        'durability', 'material')
      ->orderBy('name')->get();
  }

  static public function fetchByName($name, $userID){
    $itemType = \App\ItemTypes::where('name', $name)->first();
    return \App\Items::where('itemTypeID', $itemType->id)->where('userID', $userID)->first();
  }


  public static function fetchItemNameForAction($actionName){
    $itemNameArr = [
      'cook-flour'                            => 'Food',
      'cook-wheat'                            => 'Food',
      'convert-coal-to-carbon-nanotubes'      => 'Carbon Nanotubes',
      'convert-corpse-to-Bio-Material'        => 'Bio Material',
      'convert-corpse-to-genetic-material'    => 'Genetic Material',
      'convert-herbal-greens-to-Bio-Material' => 'Bio Material',
      'convert-meat-to-Bio-Material'          => 'Bio Material',
      'convert-plant-x-to-Bio-Material'       => 'Bio Material',
      'convert-sand-to-silicon'               => 'Silicon',
      'convert-uranium-ore-to-plutonium'      => 'Plutonium',
      'convert-wheat-to-Bio-Material'         => 'Bio Material',
      'convert-wood-to-carbon-nanotubes'      => 'Carbon Nanotubes',
      'convert-wood-to-coal'                  => 'Coal',
      'gather-stone'                          => 'Stone',
      'gather-wood'                           => 'Wood',
      'generate-electricity-with-coal'        => 'Electricity',
      'generate-electricity-with-plutonium'   => 'Electricity',
      'harvest-rubber'                        => 'Rubber',
      'harvest-wheat'                         => 'Wheat',
      'harvest-herbal-greens'                 => 'Herbal Greens',
      'harvest-plant-x'                       => 'Plant X',
      'hunt'                                  => 'Meat',
      'make-BioMeds'                          => 'BioMeds',
      'make-book'                             => 'Books',
      'make-clone'                            => 'Clones',
      'make-contract'                         => 'Contracts',
      'make-CPU'                            => 'CPU',
      "make-diesel-bulldozer"               => 'Bulldozer (diesel)',
      "make-diesel-car"                     => 'Car (diesel)',
      "make-diesel-engine"                  => 'Diesel Engines',
      "make-diesel-tractor"                 => 'Tractor (diesel)',
      "make-electric-chainsaw"              => 'Chainsaw (electric)',
      "make-electric-jackhammer"            => 'Jackhammer (electric)',
      "make-electric-motor"                 => 'Electric Motors',
      "make-gas-chainsaw"                   => 'Chainsaw (gasoline)',
      "make-gas-jackhammer"                 => 'Jackhammer (gasoline)',
      "make-gas-motor"                      => 'Gas Motors',
      "make-gasoline-bulldozer"             => 'Bulldozer (gasoline)',
      "make-gasoline-car"                   => 'Car (gasoline)',
      "make-gasoline-engine"                => 'Gasoline Engines',
      "make-gasoline-tractor"               => 'Tractor (gasoline)',
      "make-HerbMed"                        => 'Herb Meds',
      "make-iron-axe"                       => 'Axe (iron)',
      "make-iron-handmill"                  => 'Handmill (iron)',
      "make-iron-pickaxe"                   => 'Pickaxe (iron)',
      "make-iron-saw"                       => 'Saw (iron)',
      "make-iron-shovel"                    => 'Shovel (iron)',
      "make-nanites"                        => 'Nanites',
      "make-NanoMeds"                       => 'NanoMeds',
      "make-paper"                          => 'Paper',
      "make-radiation-suit"                 => 'Chem Lab',
      "make-robot"                          => 'Robots',
      "make-rocket-engine"                  => 'Rocket Engines',
      "make-satellite"                      => 'Satellite',
      "make-solar-panel"                    => 'Solar Panels',
      "make-steel-axe"                      => 'Axe (steel)',
      "make-steel-handmill"                 => 'Handmill (steel)',
      "make-steel-pickaxe"                  => 'Pickaxe (steel)',
      "make-steel-saw"                      => 'Saw (steel)',
      "make-steel-shovel"                   => 'Shovel (steel)',
      "make-stone-axe"                      => 'Axe (stone)',
      "make-stone-handmill"                 => 'Handmill (stone)',
      "make-stone-pickaxe"                  => 'Pickaxe (stone)',
      "make-stone-saw"                      => 'Saw (stone)',
      "make-stone-shovel"                   => 'Shovel (stone)',
      "make-tire"                           => 'Chem Lab',
      "mill-flour"                          => 'Flour',
      "mill-log"                            => 'Logs',
      "mine-coal"                           => 'Coal',
      "mine-copper-ore"                     => 'Copper Ore',
      "mine-iron-ore"                       => 'Iron Ore',
      "mine-sand"                           => 'Sand',
      "mine-stone"                          => 'Stone',
      "mine-uranium-ore"                    => 'Uranium Ore',
      "pump-oil"                            => 'Oil',
      "refine-oil"                          => 'Oil Refinery',
      "smelt-copper"                        => 'Copper Ingots',
      "smelt-iron"                          => 'Iron Ingots',
      "smelt-steel"                         => 'Steel Ingots',
      "transfer-electricity-from-solar-power-plant" => 'Electricity',
    ];
    return $itemNameArr[$actionName];
  }

  public static function fetchItemNamesForEquipment(){
    return [
      'Axe (iron)', 'Axe (steel)', 'Axe (stone)', 'Bulldozer (diesel)',
      'Bulldozer (gasoline)', 'Car (diesel)', 'Car (gasoline)',
      'Chainsaw (electric)', 'Chainsaw (gasoline)', 'Handmill (iron)',
      'Handmill (stone)', 'Handmill (steel)', 'Jackhammer (electric)',
      'Jackhammer (gasoline)', 'Pickaxe (iron)', 'Pickaxe (stone)',
      'Pickaxe (steel)', 'Radiation Suit', 'Saw (iron)', 'Saw (steel)',
      'Saw (stone)', 'Shovel (iron)', 'Shovel (steel)',
      'Shovel (stone)', 'Tractor (diesel)', 'Tractor (gasoline)',
    ];
  }

  public static function fetchItemsInContracts(){
    $contracts = \App\Contracts::
      orWhere(function($query){
        $query->where('active', 1)
              ->where('category', 'buyOrder');
      })->orWhere(function($query){
        $query->where('active', 1)
              ->where('category', 'sellOrder');
      })->select('itemTypeID')->distinct()->get();
    $itemsArr = [];
    foreach($contracts as $contract){
      $itemsArr[$contract->itemTypeID] = \App\ItemTypes::find($contract->itemTypeID)->name;
    }
    $alphaItemsArr = $itemsArr;
    sort($alphaItemsArr);
    $finalArr = [];
    foreach($alphaItemsArr as $item){
      $finalArr[array_search($item, $itemsArr)] = $item;
    }
    return $finalArr;
  }

  static public function fetchActionItemInput($actionName){
    $actionReqs = [
      'cook-flourCampfire'                    => ['Flour' => 1, 'Wood'=> 1],
      'cook-flourKitchen'                     => ['Flour' => 10, 'Wood'=> 5],
      'cook-flourFood Factory'                => ['Flour' => 1000, 'Electricity'=> 100],
      'cook-meatCampfire'                     => ['Meat' => 1, 'Wood'=> 1],
      'cook-meatKitchen'                      => ['Meat' => 10, 'Wood'=> 5],
      'cook-meatFood Factory'                 => ['Meat' => 1000, 'Electricity'=> 100],
      'convert-coal-to-carbon-nanotubes'      => ['Coal' => 1000, 'Electricity'=> 100],
      'convert-corpse-to-genetic-material'    => ['Corpse' => 1, 'Electricity' => 1000],
      'convert-corpse-to-Bio-Material'        => ['Corpse' => 1, 'Electricity' => 100],
      'convert-herbal-greens-to-Bio-Material' => ['Herbal Greens' => 100, 'Electricity' => 100],
      'convert-plant-x-to-Bio-Material'       => ['Plant X' => 100, 'Electricity' => 100],
      'convert-sand-to-silicon'               => ['Sand' => 1000, 'Electricity' => 100],
      'convert-meat-to-Bio-Material'          => ['Meat' => 100, 'Electricity' => 100],
      'convert-uranium-ore-to-plutonium'      => ['Uranium Ore' => 1000, 'Electricity' => 1000],
      'convert-wheat-to-Bio-Material'         => ['Wheat' => 100, 'Electricity' => 100],
      'convert-wood-to-carbon-nanotubes'      => ['Wood' => 1000, 'Electricity'=> 100],
      'convert-wood-to-coal'                  => ['Wood' => 1000, 'Electricity'=> 1000],
      'generate-electricity-with-coal'        => ['Coal' => 100],
      'generate-electricity-with-plutonium'   => ['Plutonium' => 100],
      'make-BioMeds'
         => ['HerbMeds' => 10, 'Bio Material' => 10,'Electricity' => 10],
      'make-book'                             => ['Paper' => 100],
      'make-contract'                         => ['Paper' => 1],
      'make-cpu'                              => ['Silicon' => 100, 'Copper Ingots' => 100, 'Electricity' => 1000],
      'make-clone'                            => ['Genetic Material' => 1000, 'Electricity'=> 100000],
      'make-diesel-bulldozer'                 => ['Steel Ingots' => 250,
        'Diesel Engines'=> 1, 'Copper Ingots' => 50, 'Electricity'=> 1000],
      'make-diesel-car'                       => ['Steel Ingots' => 50, 'Tires' => 4,
        'Diesel Engines'=> 1, 'Copper Ingots' => 10, 'Electricity'=> 1000],
      'make-diesel-engine'                    => ['Steel Ingots' => 40,
        'Iron Ingots'=> 40, 'Copper Ingots'   => 20, 'Electricity'=> 100],
      'make-diesel-tractor'                   => ['Steel Ingots' => 100, 'Tires' => 4,
        'Diesel Engines'=> 1, 'Copper Ingots' => 20, 'Electricity'=> 1000],
      'make-electric-chainsaw'                => ['Steel Ingots' => 10,
        'Electric Motors'=> 1],
      'make-electric-jackhammer'              => ['Steel Ingots' => 10,
        'Electric Motors'=> 1],
      'make-electric-motor'                   => ['Steel Ingots' => 10,
        'Iron Ingots'=> 10, 'Copper Ingots'   => 5, 'Electricity'=> 25],
      'make-gas-chainsaw'                     => ['Steel Ingots' => 10,
        'Electric Motors'=> 1],
      'make-gas-jackhammer'                   => ['Steel Ingots' => 10,
        'Electric Motors'=> 1],
      'make-gasoline-bulldozer'               => ['Steel Ingots' => 250,
        'Gasoline Engines'=> 1, 'Copper Ingots' => 50, 'Electricity'=> 1000],
      'make-gasoline-car'                     => ['Steel Ingots' => 50, 'Tires' => 4,
        'Gasoline Engines'=> 1, 'Copper Ingots' => 10, 'Electricity'=> 1000],
      'make-gasoline-engine'                  => ['Steel Ingots' => 40,
        'Iron Ingots'=> 40, 'Copper Ingots' => 20, 'Electricity'=> 100],
      'make-gas-motor'                        => ['Steel Ingots' => 10,
        'Iron Ingots'=> 10, 'Copper Ingots' => 5, 'Electricity'=> 25],
      'make-gasoline-tractor'                 => ['Steel Ingots' => 100, 'Tires' => 4,
        'Gasoline Engines'=> 1, 'Copper Ingots' => 20, 'Electricity'=> 1000],
      'make-HerbMed'                          => ['Herbal Greens' => 10],
      'make-iron-axe'                         => ['Iron Ingots' => 1, 'Wood'=> 1],
      'make-iron-handmill'                    => ['Iron Ingots' => 1, 'Wood'=> 1],
      'make-iron-pickaxe'                     => ['Iron Ingots' => 1, 'Wood'=> 1],
      'make-iron-saw'                         => ['Iron Ingots' => 1, 'Wood'=> 1],
      'make-iron-shovel'                      => ['Iron Ingots' => 1, 'Wood'=> 1],
      'make-nanites'                          => ['Silicon' => 100,
        'Carbon Nanotubes' => 100, 'Electricity'=> 1000],
      'make-NanoMeds'                         => ['BioMeds' => 10, 'Nanites' => 10,
        'Electricity'=> 1000],
      'make-paper'                            => ['Wood' => 1],
      'make-radiation-suit'                   => ['Rubber' => 100,
        'Electricity'=> 100],
      'make-robot'                     => ['Steel Ingots' => 100, 'CPU' => 10,
        'Electric Motors'=> 10, 'Copper Ingots' => 1000, 'Electricity'=> 100000],
      'make-rocket-engine'                     => ['Steel Ingots' => 100,
        'Jet Fuel' => 1000, 'Iron Ingots' => 1000, 'Electricity'=> 1000],
      'make-satellite'                     => ['Steel Ingots' => 100, 'CPU' => 1,
        'Solar Panels'=> 5, 'Copper Ingots' => 100, 'Electricity'=> 100, 'Rocket Engines'=> 1],
      'make-solar-panel'                     => ['Steel Ingots' => 100,
        'Copper Ingots' => 100, 'Silicon' => 100, 'Electricity'=> 100],
      'make-steel-axe'                        => ['Steel Ingots' => 1, 'Wood'=> 1],
      'make-steel-handmill'                   => ['Steel Ingots' => 1, 'Wood'=> 1],
      'make-steel-pickaxe'                    => ['Steel Ingots' => 1, 'Wood'=> 1],
      'make-steel-saw'                        => ['Steel Ingots' => 1, 'Wood'=> 1],
      'make-steel-shovel'                     => ['Steel Ingots' => 1, 'Wood'=> 1],
      'make-stone-axe'                        => ['Stone' => 1, 'Wood'=> 1],
      'make-stone-handmill'                   => ['Stone' => 1, 'Wood'=> 1],
      'make-stone-pickaxe'                    => ['Stone' => 1, 'Wood'=> 1],
      'make-stone-saw'                        => ['Stone' => 1, 'Wood'=> 1],
      'make-stone-shovel'                     => ['Stone' => 1, 'Wood'=> 1],
      'make-tire'                             => ['Rubber' => 10,
        'Electricity'=> 10],
      'mill-flour'                            => ['Wheat'=>10],
      'mill-flourGristmill'                   => ['Wheat'=>100],
      'mill-log'                              => ['Logs'=>10],
      'mill-logSawmill'                       => ['Logs'=>100],
      'pump-oil'                              => ['Electricity'=>10],
      'refine-oil'                            => ['Electricity'=>100, 'Oil'=>100],
      'smelt-copperElectric Arc Furnace'      => ['Electricity'=>1000, 'Copper Ore'=>1000],
      'smelt-copperLarge Furnace'             => ['Coal'=>100, 'Copper Ore'=>100],
      'smelt-copperSmall Furnace'             => ['Coal'=>10, 'Copper Ore'=>10],
      'smelt-ironElectric Arc Furnace'      => ['Electricity'=>1000, 'Iron Ore'=>1000],
      'smelt-ironLarge Furnace'             => ['Coal'=>100, 'Iron Ore'=>100],
      'smelt-ironSmall Furnace'             => ['Coal'=>10, 'Iron Ore'=>10],
      'smelt-steelElectric Arc Furnace'      => ['Electricity'=>1000, 'Iron Ingots'=>1000],
      'smelt-steelLarge Furnace'             => ['Coal'=>100, 'Iron Ingots'=>100],
      'smelt-steelSmall Furnace'             => ['Coal'=>10, 'Iron Ingots'=>10],
    ];
    if(!in_array($actionName, array_keys($actionReqs))){
      return null;
    }

    return $actionReqs[$actionName];
  }

  static public function fetchInventory(){
    return Items::where('userID', Auth::id())
      ->join('itemTypes', 'items.itemTypeID', 'itemTypes.id')
      ->where('quantity', '>', 0 )
      ->select('items.id', 'itemTypeID', 'quantity', 'name', 'description',
        'durability', 'material')
      ->orderBy('name')->get();
  }

  public static function fetchItemsForBuyOrders(){
    return \App\BuyOrders::join('itemTypes', 'buyOrders.itemTypeID', 'itemTypes.id')
      ->where('buyOrders.active', 1)->select('itemTypes.name', 'itemTypes.id')
      ->orderBy('itemTypes.name')->distinct()->get();
  }

  static public function fetchTotalQuantity($userID){
    return \App\Items::where('userID', $userID)->where('countable', true)
      ->sum('quantity');
  }

  static public function doTheyHave($name, $quantity){
    $item = Items::fetchByName($name, Auth::id());
    if ($item->quantity >= $quantity){
      return true;
    }
    return false;
  }

  public static function make($itemName, $quantity, $contractorID, $agentID){
    $item = \App\Items::fetchByName($itemName, $contractorID);
    $item->quantity += $quantity;
    $item->save();
    $status = $itemName . ": <span class='fp'>+" . number_format($quantity) . "</span> ";
    if ($contractorID == $agentID){
      $status .= " [" . number_format($item->quantity) . "]";
    }
    return $status;
  }

  public static function new($itemTypeID, $userID){
    $item = new \App\Items;
    $item->itemTypeID = $itemTypeID;
    $item->userID = $userID;
    $item->save();
  }

  public function type(){
    return $this->hasOne('App\ItemTypes', 'itemTypesID');
  }

  public static function use($itemArr, $contractorID){
    $status = "";
    foreach($itemArr as $itemName => $quantity){
      $item = \App\Items::fetchByName($itemName, $contractorID);
      if ($item->quantity < $quantity){
        return ['error'=>'You need at least ' . number_format($quantity) . " " . $itemName];
      }
    }
    foreach($itemArr as $itemName => $quantity){
      $item = \App\Items::fetchByName($itemName, $contractorID);
      $item->quantity -= $quantity;
      $item->save();
      $status .= $itemName . ": <span class='fn'>-" . number_format($quantity) . " "
        . "</span> [" . number_format($item->quantity) . "] ";
    }
    return ['status' => $status];
  }

  public static function useMeds($itemID){
    $item = \App\Items::find($itemID);
    if ($item->quantity < 1){
      echo json_encode(['error' => "You don't have any of meds to take."]);
      return;
    }
    $itemType = \App\ItemTypes::find($item->itemTypeID);
    if ($itemType->name == 'HerbMeds'){
      $minutes = 5;
      $caption = "5 minutes ";
    } else if ($itemType->name == 'BioMeds'){
      $minutes = 5 * 60;
      $caption = "5 hours ";
    } else if ($itemType->name == 'NanoMeds'){
      $minutes = 5 * 60 * 24;
      $caption = '5 days ';
    }
    $item->quantity--;
    $item->save();
    $user = \App\User::find(\Auth::id());
    $user->minutes += $minutes;
    $user->save();
    return ['status' => "<span class='actionInput'>" . $itemType->name
      . ": <span class='fn'>-1</span> [" . number_format($item->quantity)
      . "]</span> &rarr; Offline Time: <span class='fp'>+" . $caption . "</span>"];
  }
}
