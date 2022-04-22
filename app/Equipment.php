<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Equipment;
use Illuminate\Support\Facades\Auth;

class Equipment extends Model
{
  protected $table = 'equipment';

  public static function doTheyNeedToSwitch($itemName, $userID){
    
  }

  public static function fetchUses($material, $durability){
    $materialUses = ['stone' => 10, 'iron' => 100, 'steel' => 1000];
    $durabilityCaption = [null, 'horrible', 'poor', 'average', 'good', 'great'];
    return $materialUses[$material] * array_search($durability, $durabilityCaption);
  }

  static public function fetch(){
    return Equipment::where('userID', Auth::id())
      ->join('itemTypes', 'equipment.itemTypeID', 'itemTypes.id')
      ->select('equipment.id', 'itemTypeID', 'uses', 'totalUses', 'name', 'description',
        'durability', 'material')
      ->orderBy('name')->get();
  }

  public function type(){
    return $this->hasOne('App\ItemTypes', 'id', 'itemTypeID');
  }

  public static function useEquipped($userID){
    $labor = Labor::where('userID', $userID)->first();
    if ($labor->equipped == null){
      return false;
    }
    $selfCaption = " Their ";
    if ($userID == Auth::id()){
      $selfCaption = " Your ";
    }
    $equipment = Equipment::find($labor->equipped);
    $itemType = ItemTypes::find($equipment->itemTypeID);
    $equipment->uses--;
    $equipment->save();
    if ($equipment->uses == 0){

      $status = $selfCaption . $itemType->name . " was destroyed in the process.";
      Equipment::destroy($labor->equipped);
      $labor->equipped = null;
      $labor->save();
      return $status;
    }
    return $selfCaption . $itemType->name . " is now at "
      . number_format($equipment->uses / $equipment->totalUses * 100, 2 ) . "%. ";
  }
}
