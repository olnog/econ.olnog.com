<?php

namespace App;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class BuildingTypes extends Model
{
  protected $table = 'buildingTypes';



  public static function fetch(){
      return \App\BuildingTypes::where('farming', false)->orderBy("name", 'asc')->get();
  }

  public static function fetchBuildingCost($buildingType){
    $buildingCosts = [
      'Bio Lab' => ['Steel Ingots' => 2000, 'Stone' => 500, 'Copper Ingots' => 1000, 'Iron Ingots'=> 2000],
      'Campfire' => ['Wood' => 10],
      'Centrifuge' => ['Steel Ingots' => 90000, 'Copper Ingots' => 9000, 'Stone'=>9000],
      'Chem Lab' => ['Steel Ingots' => 1000, 'Stone' => 300, 'Copper Ingots' => 1000, 'Iron Ingots'=> 1000],
      'Clone Vat' => ['Steel Ingots' => 50000, 'Stone' => 10000, 'Copper Ingots' => 50000],
      'Coal Power Plant' => ['Iron Ingots'=> 2000, 'Copper Ingots'=> 2000, 'Stone'=>400],
      'CPU Fabrication Plant' => ['Steel Ingots'=> 50000, 'Copper Ingots'=> 50000, 'Stone' => 10000],
      'Electric Arc Furnace' => ['Steel Ingots' => 1000, 'Copper Ingots' => 5000, 'Stone' => 600],
      'Food Factory' => ['Steel Ingots' => 2000, 'Copper Ingots' => 2000, 'Stone' => 1000],
      'Garage' => ['Steel Ingots' => 10000, 'Stone' => 1000, 'Copper Ingots' => 1000],
      'Gristmill' => ['Stone' => 500, 'Wood' => 500],
      'Herbal Greens Field' => [],
      'Kitchen' => ['Stone' => 100, 'Wood' => 100],
      'Large Furnace' => ['Stone' => 1000],
      'Machine Shop' => ['Steel Ingots' => 1000, 'Stone' => 100, 'Copper Ingots' => 100],
      'Mine' => ['Wood' => 1000],
      'Nano Lab' => ['Steel Ingots' => 1000, 'Copper Ingots'=> 10000, 'CPU'=> 100, 'Electric Motors'=> 100, 'Stone' => 1200 ],
      'Nuclear Power Plant' => ['Steel Ingots' => 100000, 'Copper Ingots' => 100000, 'Stone'=>20000],
      'Oil Refinery' => ['Copper Ingots' => 1000, 'Steel Ingots' => 10000, 'Stone' => 1000],
      'Oil Well' => ['Steel Ingots' => 500, 'Iron Ingots' => 500, 'Copper Ingots' => 100, 'Stone' => 100],
      'Plant X Field' => [],
      'Propulsion Lab' => ['Steel Ingots' => 100000, 'Copper Ingots' => 10000,  'Stone'=>11000],
      'Robotics Lab' => ['Steel Ingots' => 100000, 'Copper Ingots' => 10000, 'CPU'=> 100, 'Electric Motors' => 1000, 'Stone' => 11000],
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
}
