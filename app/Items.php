<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Items extends Model
{
  protected $table = 'items';

  static public function fetchByName($name, $userID){
    $itemType = \App\ItemTypes::where('name', $name)->first();
    return \App\Items::where('itemTypeID', $itemType->id)->where('userID', $userID)->first();
  }

  static public function fetchByItemTypeID($itemTypeID){
    return \App\Items::where('userID', Auth::id())->where('itemTypeID', $itemTypeID)->first();
  }

  static public function fetch(){
    return Items::where('userID', Auth::id())
      ->join('itemTypes', 'items.itemTypeID', 'itemTypes.id')
      ->select('items.id', 'itemTypeID', 'quantity', 'name', 'description',
        'durability', 'material')
      ->orderBy('name')->get();
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
      'generate-electricity-with-coal'        => ['Coal' => 1000],
      'generate-electricity-with-plutonium'   => ['Plutonium' => 1000],
      'make-BioMeds'
         => ['HerbMeds' => 10, 'Bio Material' => 10,'Electricity' => 10],
      'make-book'                             => ['Paper' => 100],
      'make-contract'                          => ['Paper' => 1],
      'make-clone'                            => ['Genetic Material' => 1000, 'Electricity'=> 100000],
      'make-diesel-bulldozer'                 => ['Steel Ingots' => 250,
        'Diesel Engines'=> 1, 'Copper Ingots' => 50, 'Electricity'=> 1000],
      'make-diesel-car'                       => ['Steel Ingots' => 50, 'Tires' => 4,
        'Diesel Engines'=> 1, 'Copper Ingots' => 10, 'Electricity'=> 1000],
      'make-diesel-engine'                    => ['Steel Ingots' => 40,
        'Iron Ingots'=> 40, 'Copper Ingots'   => 20, 'Electricity'=> 100],
      'make-diesel-tractor'                   => ['Steel Ingots' => 100,
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
      'make-gasoline-tractor'                 => ['Steel Ingots' => 100,
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
      'smelt-steelElectric Arc Furnace'      => ['Electricity'=>1000, 'Steel Ingots'=>1000],
      'smelt-steelLarge Furnace'             => ['Coal'=>100, 'Steel Ingots'=>100],
      'smelt-steelSmall Furnace'             => ['Coal'=>10, 'Steel Ingots'=>10],
    ];
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

  static public function fetchTotalQuantity($userID){
    $items = \App\Items::where('userID', $userID)->where('countable', true)->get();
    $total = 0;
    foreach ($items as $item){
      $total += $item->quantity;
    }
    return $total;
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
}
