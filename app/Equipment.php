<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Equipment;
use Illuminate\Support\Facades\Auth;

class Equipment extends Model
{
  protected $table = 'equipment';

  public static function doTheyHave($itemName, $userID){
    return \App\Equipment::fetchByName($itemName, $userID) != null;
  }

  public static function fetchByName($itemName, $userID){
    $allEquipment = \App\Equipment::where('userID', $userID)->get();
    foreach ($allEquipment as $equipment){
      $itemType = \App\ItemTypes::find($equipment->itemTypeID);
      if (substr($itemType->name, 0, strlen($itemName)) == $itemName){
        return $equipment;
      }
    }
    return null;
  }

  public static function fetchFuel(){
    return [
      'electric' => 'Electricity',
      'diesel' => 'Diesel Fuel',
      'gasoline' => 'Gasoline',
    ];
  }

  static public function fetch(){
    return Equipment::where('userID', Auth::id())
      ->join('itemTypes', 'equipment.itemTypeID', 'itemTypes.id')
      ->select('equipment.id', 'itemTypeID', 'uses', 'totalUses', 'name',
        'description', 'durability', 'material')
      ->orderBy('name')->get();
  }

  public function type(){
    return $this->hasOne('App\ItemTypes', 'id', 'itemTypeID');
  }

  public static function useEquipped($itemName, $userID){
    $fuelArr = \App\Equipment::fetchFuel();
    $fuelStatus = "";
    $couldTheySwitch = \App\Labor::couldTheySwitch($itemName, $userID);
    $labor = Labor::where('userID', $userID)->first();
    if ($labor->equipped == null && !$couldTheySwitch){
      return false;
    }
    if ($labor->equipped == null && $couldTheySwitch){
      $switch = \App\Labor::switchEquipped($itemName, $userID);
      if (!$switch){
        return $switch;
      }
    }
    $equipment = Equipment::find($labor->equipped);
    $itemType = ItemTypes::find($equipment->itemTypeID);
    if (substr($itemType->name, 0, strlen($itemName)) != $itemName){
      $switch = \App\Labor::switchEquipped($itemName, $userID);
      $labor = \App\Labor::where('userID', $userID)->first(); // idk if these will automatically refresh
      $equipment = Equipment::find($labor->equipped);
      $itemType = ItemTypes::find($equipment->itemTypeID);
      if (!$switch){
        return false;
      }
    }
    foreach ($fuelArr as $fueledBy => $fuelName){
      if (!str_contains($itemName, $fueledBy)){
        continue;
      }
      $fuel = \App\Items::fetchByName($fuelName, $userID);
      $fuelReq = 100;
      if ($fuelName == 'Electricity'){
        $fuelReq = 1000;
      }
      if ($fuel->quantity < $fuelReq){
        return false;
      }
      $fuel->quantity -= $fuelReq;
      $fuel->save();
      $fuelStatus = $fuelName . ": <span class='fn'> -" . $fuelReq . " </span> ["
        . number_format($fuel->quantity) . "]";
    }
    $equipment->uses--;
    $equipment->save();
    if ($equipment->uses == 0){
      $status =  $itemType->name . ": &empty;";
      Equipment::destroy($labor->equipped);
      $labor->equipped = null;
      $labor->save();
      return $status . $fuelStatus;
    }
    return $itemType->name . ": <span class='fn'>"
      . number_format($equipment->uses / $equipment->totalUses * 100, 2 ) . "%</span> " . $fuelStatus;
  }


  public static function whichOfTheseCanTheyUse($equipmentArr, $userID){
    if ($equipmentArr == null){
      return null;
    }
    $fuelArr = \App\Equipment::fetchFuel();
    $usableEquipArr = [];
    foreach ($equipmentArr as $equipmentName){
      $fuelRequired = false;
      $enoughFuel = false;
      if (!\App\Equipment::doTheyHave($equipmentName, $userID)){
        continue;
      }
      foreach ($fuelArr as $fueledBy => $itemName){
        if (!str_contains($equipmentName, $fueledBy)){
          continue;
        }
        $fuelRequired = true;
        $fuelAmountReq = 100;
        if ($itemName == 'Electricity'){
          $fuelAmountReq = 1000;
        }
        if (\App\Items::fetchByName($itemName, $userID)->quantity >= $fuelAmountReq){
          $enoughFuel = true;
        }
      }
      if (!$fuelRequired || ($fuelRequired && $enoughFuel)){
        $usableEquipArr[] = $equipmentName;
      }
    }
    return $usableEquipArr;
  }

  public static function whichEquipment($actionName){
    $equipmentArr = [
      'chop-tree'             => ['Chainsaw (electric)','Chainsaw (gasoline)',
                                  'Axe'],
      'explore'               => ['Car (gasoline)', 'Car (diesel)'],
      'harvest-wheat'         => ['Tractor (gasoline)', 'Tractor (diesel)'],
      'harvest-plant-x'       => ['Tractor (gasoline)', 'Tractor (diesel)'],
      'harvest-herbal-greens' => ['Tractor (gasoline)', 'Tractor (diesel)'],
      'harvest-rubber'        => ['Tractor (gasoline)', 'Tractor (diesel)'],
      'mine-sand'             => ['Bulldozer (gasoline)', 'Bulldozer (diesel)',
                                  'Shovel'],
      'mine-coal'             => ['Jackhammer (gasoline)', 'Jackhammer (electric)',
                                  'Pickaxe'],
      'mine-iron-ore'         => ['Jackhammer (gasoline)', 'Jackhammer (electric)',
                                  'Pickaxe'],
      'mine-stone'             => ['Jackhammer (gasoline)', 'Jackhammer (electric)',
                                  'Pickaxe'],
      'mine-copper-ore'        => ['Jackhammer (gasoline)', 'Jackhammer (electric)',
                                  'Pickaxe'],
      'mine-uranium-ore'       => ['Jackhammer (gasoline)', 'Jackhammer (electric)',
                                  'Pickaxe'],
    ];
    if (!in_array($actionName, array_keys($equipmentArr))){
      return null;
    }
    return $equipmentArr[$actionName];
  }
}
