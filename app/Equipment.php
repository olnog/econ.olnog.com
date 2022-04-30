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
      if ($fuel->quantity < 100){
        return false;
      }
      $fuel->quantity -= 100;
      $fuel->save();
      $fuelStatus = $fuelName . ": <span class='fn'> -100 </span> ["
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
        if (\App\Items::fetchByName($itemName, $userID)->quantity >= 100){
          $enoughFuel = true;
        }
      }
      if (!$fuelRequired || ($fuelRequired && $enoughFuel)){
        $usableEquipArr[] = $equipmentName;
      }
    }
    return $usableEquipArr;
  }
}
