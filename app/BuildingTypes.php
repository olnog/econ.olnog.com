<?php

namespace App;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class BuildingTypes extends Model
{
  protected $table = 'buildingTypes';

  public static function canTheyRepair($buildingName, $agentID, $contractorID){
    $action = \App\Actions::fetchByName($agentID, 'build');
    if ($action->rank == 0 || !$action->unlocked){
      return false;
    }
    $buildingCosts = \App\BuildingTypes::fetchBuildingCost($buildingName);
    foreach ($buildingCosts as $material=>$cost){
      $item = \App\Items::fetchByName($material, $contractorID);
      if ($item->quantity < ceil($cost * ($action->rank * .5))){
        return false;
      }
    }
    return true;
  }

  public static function fetch(){
      return \App\BuildingTypes::where('farming', false)->get();
  }

  public static function fetchBuildingCost($buildingType){
    $buildingCosts = [
      'Bio Lab' => ['Steel Ingots' => 2000, 'Stone' => 500, 'Copper Ingots' => 1000, 'Iron Ingots'=> 2000],
      'Campfire' => ['Wood' => 10],
      'Centrifuge' => ['Steel Ingots' => 10000, 'Copper Ingots' => 1000, 'Stone'=>1100],

      'Chem Lab' => ['Steel Ingots' => 1000, 'Stone' => 300, 'Copper Ingots' => 1000, 'Iron Ingots'=> 1000],
      'Clone Vat' => ['Steel Ingots' => 1000000, 'Stone' => 100000, 'Copper Ingots' => 10000],
      'Coal Power Plant' => ['Iron Ingots'=> 1000, 'Copper Ingots'=> 1000, 'Stone'=>200],
      'CPU Fabrication Plant' => ['Steel Ingots'=> 10000, 'Copper Ingots'=> 10000, 'Stone' => 2000],
      'Electric Arc Furnace' => ['Steel Ingots' => 1000, 'Copper Ingots' => 5000, 'Stone' => 600],
      'Food Factory' => ['Steel Ingots' => 2000, 'Copper Ingots' => 2000, 'Stone' => 1000],
      'Garage' => ['Steel Ingots' => 2000, 'Stone' => 200, 'Copper Ingots' => 200],

      'Gristmill' => ['Stone' => 500, 'Wood' => 500],
      'Herbal Greens Field' => [],
      'Kitchen' => ['Stone' => 100, 'Wood' => 100],
      'Large Furnace' => ['Stone' => 1000],
      'Machine Shop' => ['Steel Ingots' => 1000, 'Stone' => 100, 'Copper Ingots' => 100],
      'Mine' => ['Wood' => 1000],
      'Nano Lab' => ['Steel Ingots' => 1000, 'Copper Ingots'=> 10000, 'CPU'=> 1000, 'Electric Motors'=> 100, 'Stone' => 1200 ],
      'Nuclear Power Plant' => ['Steel Ingots' => 100000, 'Copper Ingots' => 100000, 'Stone'=>20000],
      'Oil Refinery' => ['Copper Ingots' => 1000, 'Steel Ingots' => 10000, 'Stone' => 1000],
      'Oil Well' => ['Steel Ingots' => 500, 'Iron Ingots' => 500, 'Copper Ingots' => 100, 'Stone' => 100],
      'Plant X Field' => [],
      'Propulsion Lab' => ['Steel Ingots' => 100000, 'Copper Ingots' => 10000,  'Stone'=>11000],
      'Robotics Lab' => ['Steel Ingots' => 100000, 'Copper Ingots' => 10000, 'CPU'=> 1000, 'Electric Motors' => 1000, 'Stone' => 11000],
      'Rubber Plantation' => [],
      'Sawmill' => ['Iron Ingots' => 10, 'Stone' => 100, 'Wood' => 1000],
      'Small Furnace' => ['Stone' => 100],
      'Solar Power Plant' => ['Steel Ingots' => 2000, 'Solar Panels' => 100, 'Copper Ingots' => 2000, 'Stone' => 500],
      'Solar Panel Fabrication Plant' => ['Steel Ingots'=> 1000, 'Copper Ingots'=> 1000, 'Stone' => 200],
      'Warehouse' => ['Stone' => 100, 'Wood' => 1000],
      'Wheat Field' => [],
    ];
    if ($buildingType == null){
      return $buildingCosts;
    }
    return $buildingCosts[$buildingType];
  }

  public static function fetchByName($buildingName){
    return BuildingTypes::where('name', $buildingName)->first();
  }

  public static function fetchDurability($constructionSkill){

    $durabilityArr = [
      null, 'horribly built', 'poorly built', 'average built', 'well-built',
      'excellently built'
    ];
    if ($constructionSkill == null){
      return $durabilityArr;
    }
    return $durabilityArr[$constructionSkill];
  }
}
