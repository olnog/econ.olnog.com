<?php

namespace App;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Buildings extends Model
{
  protected $table = 'buildings';

  public static function build($buildingName, $agentID, $contractorID){
    $contractor = \App\User::find($contractorID);
    $labor = \App\Labor::where('userID', $agentID)->first();
    $action = \App\Actions::fetchByName($agentID, 'build');
    if (\App\Buildings::doesItExist($buildingName, $contractorID)){
      return [
        'error' => "You've already built this."
      ];
    } else if ($contractor->buildingSlots < 1 ){
      return [
        'error' => "You don't have enough building slots to build this. Either buy more land or explore."
      ];
    } else if (!\App\Buildings::canYouBuild($buildingName, $contractorID)){
      return ['error' => "Not enough resources to build this." ];
    } else if (!$action->unlocked || $action->rank == 0){
      return ['error' => "You haven't unlocked the action yet." ];
    }
    doAction($agentID, $action->id);

    $numOfUses = 100 * $action->rank;
    $status = "You built a " . $buildingName . ". You spent: ";
    $contractorStatus = $status;
    $agentStatus = $status;
    $buildingCosts = \App\BuildingTypes::fetchBuildingCost($buildingName);

    foreach ($buildingCosts as $material => $cost){
      $buildingCost = \App\Items::fetchByName($material, $contractorID);
      $buildingCost->quantity -= $cost;
      $buildingCost->save();

      $agentStatus .= number_format($cost) . " " . $material . " ";
      $contractorStatus .= number_format($cost) . " " . $material . " ["
        . number_format($buildingCost->quantity) . "] ";
    }
    $buildingType = \App\BuildingTypes::fetchByName($buildingName);
    $building = new \App\Buildings;
    $building->buildingTypeID = $buildingType->id;
    $building->userID = $contractorID;
    $building->uses = $numOfUses;
    $building->totalUses = $numOfUses;
    $building->repairedTo = $numOfUses;
    $building->save();
    $contractor->buildingSlots--;
    $contractor->save();

    \App\History::new($contractorID, 'buildings', $contractorStatus);
    return ['status' => $agentStatus];
  }

  public static function canYouBuild($buildingName, $userID){
    //$userID = Auth::id();
    $buildingCosts = \App\BuildingTypes::fetchBuildingCost($buildingName);
    foreach ($buildingCosts as $material=>$cost){
      $item = \App\Items::fetchByName($material, $userID);
      if ($item->quantity < $cost ){
        return false;
      }
    }
    return true;
  }

  public static function canTheyHarvest($fieldName, $userID){
    $buildingType = \App\BuildingTypes::fetchByName($fieldName);
    $fields = \App\Buildings::where('userID', $userID)
    ->where('harvestAfter', '<', date('Y-m-d H:i:s'))
    ->where('buildingTypeID', $buildingType->id)->get();
    return count($fields) > 0;
  }

  public static function canTheyRebuild($buildingID, $userID){
    $building = \App\Buildings::find($buildingID);
    $durabilityPos = array_search($building->durabilityCaption, \App\BuildingTypes::fetchDurability(null));
    return $durabilityPos <= $constructionSkill->rank;
  }

  public static function destroyBuilding($id){
    $user = Auth::user();
    $building = \App\Buildings::find($id);
    $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
    if ($buildingType->name == 'Warehouse'
      && $user->itemCapacity - \App\Items::fetchTotalQuantity(Auth::id()) < 10000){
      echo json_encode([
        'error' =>
        "You don't have enough item capacity to destroy this warehouse. Get rid of some items or increase your item capacity first. "
      ]);
      return;
    }
    \App\Buildings::destroy($id);
    if ($buildingType->name == 'Warehouse'){
      $user->itemCapacity -= 10000;
    }
    $user->buildingSlots++;
    $user->save();
  }

  public static function didTheyAlreadyBuildThis($buildingName, $userID){
    if (\App\BuildingLease::areTheyLeasingThis($buildingName, $userID)){
      return true;
    }
    $building = \App\Buildings::fetchByName($buildingName, $userID);

    if ($buildingName == 'Warehouse'){
      return false;
    } else if ($building == null || $building->uses < 1){
      return false;
    }

    return true;
  }

  public static function isItBuilt($buildingName, $userID){
    $building = \App\Buildings::fetchByName($buildingName, $userID);
    if ($building == null){
      return false;
    }

    return true;
  }
  public static function doesItExist($buildingName, $userID){
    if (\App\BuildingLease::areTheyLeasingThis($buildingName, $userID)){
      return true;
    }
    $building = \App\Buildings::fetchByName($buildingName, $userID);
    if ($building == null || $buildingName == 'Warehouse'){
      return false;
    }
    return true;
  }

  public static function fetch(){
    return [
      'built' => \App\Buildings::
      join('buildingTypes', 'buildings.buildingTypeID', 'buildingTypes.id')
      ->where('userID', Auth::id())->select('buildings.id', 'buildingTypeID',
      'uses', 'totalUses', 'durabilityCaption', 'name', 'description')
      ->select('buildings.id', 'buildingTypeID', 'uses', 'totalUses',
      'durabilityCaption', 'repairedTo', 'wheat', 'harvestAfter', 'name',
      'description', 'skill', 'actions', 'cost', 'farming')->get(),
      'repairable' => \App\Buildings::fetchRepairable(),
      'possible' => \App\BuildingTypes::all(),
      'costs' => \App\BuildingTypes::fetchBuildingCost(null),
    ];
  }
  public static function fetchBuilt(){
    return \App\Buildings::
    join('buildingTypes', 'buildings.buildingTypeID', 'buildingTypes.id')
    ->where('userID', Auth::id())->where('farming', false)->select('buildings.id', 'buildingTypeID',
    'uses', 'totalUses', 'durabilityCaption', 'name', 'description')
    ->get();
  }
  public static function fetchField($fieldName, $userID){
    $buildingType = \App\BuildingTypes::fetchByName($fieldName);
    return \App\Buildings::where('userID', $userID)
    ->where('harvestAfter', '<', date('Y-m-d H:i:s'))
    ->where('buildingTypeID', $buildingType->id)->first();
  }

  public static function fetchPossible(){
    $buildingTypes = \App\BuildingTypes::fetch();
    $possibleBuildings = [];
    foreach($buildingTypes as $buildingType){
      $building = \App\Buildings::where('userID', Auth::id())->where('buildingTypeID', $buildingType->id)->first();
      if ($building == null){
        $possibleBuildings [] = $buildingType;
      }
    }
    return $possibleBuildings;
  }

  public static function fetchRepairable(){
    $buildings = \App\Buildings::
    join('buildingTypes', 'buildings.buildingTypeID', 'buildingTypes.id')
    ->where('userID', Auth::id())->where('farming', false)
    ->select('buildings.id', 'name')->orderBy('name')->get();
    $repairableBuildings = [];
    foreach ($buildings as $building){
      if (\App\BuildingTypes::canTheyRepair($building->name, \Auth::id(), \Auth::id())){
        $repairableBuildings[] = $building->id;
      }

    }
    return $repairableBuildings;
  }

  public static function fetchByName($buildingName, $userID){
    $buildingType = \App\BuildingTypes::fetchByName($buildingName);
    return \App\Buildings::where('buildingTypeID', $buildingType->id)->where('userID', $userID)->first();
  }

  public static function howManyFields($fieldName, $userID){
    $buildingType = \App\BuildingTypes::fetchByName($fieldName);
    return \App\Buildings::where('buildingTypeID', $buildingType->id)->where('userID', $userID)->count();
  }

  public static function rebuild($id, $agentID, $contractorID){
    $action = \App\Actions::fetchByName($agentID, 'build');
    $building = \App\Buildings::find($id);
    $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
    if (!\App\Buildings::canYouBuild($buildingType->name, $contractorID)){
      return ['error' => "You don't have enough to rebuild this right now. (See what you're missing <a href='/buildingCosts'>here</a>)"];
    }
    doAction($agentID, $action->id);

    $buildingCosts = \App\BuildingTypes::fetchBuildingCost($buildingType->name);
    $materialCost = "";
    foreach ($buildingCosts as $material => $cost){
      $buildingCost = \App\Items::fetchByName($material, $agentID);
      $buildingCost->quantity -= $cost;
      $buildingCost->save();
      $materialCost .= $cost . " " . $material . " ";
    }

    $building->uses = $action * 100;
    $building->totalUses = $action * 100;
    $building->repairedTo = $action * 100;
    $building->save();
    $status = 'You rebuilt your  '
      . $buildingType->name . " with " . $materialCost . ".";
    \App\History::new($contractorID, 'buildings', $status);
    return ['status' => $status];
  }

  public static function repair($id, $agentID, $contractorID){
    $action = \App\Actions::fetchByName($agentID, 'repair');
    $building = \App\Buildings::find($id);
    $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
    $repairCostMultiplier = $action->rank * .5;

    if ($action->rank == 0 || !$action->unlocked){
      return [
        'error' => "You haven't unlocked Repair yet.",
      ];
    } else if (!\App\BuildingTypes::canTheyRepair($buildingType->name, $agentID, $contractorID)){
      return [
        'error' => "You don't have the necessary materials to repair this.",
      ];
    }
    doAction($agentID, $action->id);

    $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
    $status = "You repaired the " . $buildingType->name . " to 100%";
    $buildingCosts = \App\BuildingTypes::fetchBuildingCost($buildingType->name);
    foreach($buildingCosts as $material => $cost){
      $item = \App\Items::fetchByName($material, $contractorID);
      $item->quantity -= ceil($cost*$repairCostMultiplier);
      $item->save();
    }
    $building->uses = $building->totalUses;
    $building->save();
    return [ 'status' => $status ];
  }

  public static function solarPowerplants(){
    $buildingType = \App\BuildingTypes::fetchByName('Solar Power Plant');
    $powerPlants = \App\Buildings::where('buildingTypeID', $buildingType->id)->get();
    $increment = 100;
    foreach ($powerPlants as $powerPlant){
      if ($powerPlant->electricity < 24 * $increment){
        $powerPlant->electricity += $increment;
        $powerPlant->save();
      }
    }
  }

  public function type(){
    return $this->hasOne('App\BuildingTypes', 'id', 'buildingTypeID');
  }

  public static function use($buildingName, $userID){
    $building = \App\Buildings::fetchByName($buildingName, $userID);
    if ($building == null && \App\BuildingLease::areTheyLeasingThis($buildingName, $userID)){
      return \App\BuildingLease::use($buildingName, $userID);
    }
    $building->uses--;
    $building->save();
    if ($building->uses < 1){
      return "Your building is no longer usable. Repair it in order to keep using it.";
    }
    return "You used your " . $buildingName . ". It's now at "
      . ($building->uses / $building->totalUses * 100) . "%.";
  }
}
