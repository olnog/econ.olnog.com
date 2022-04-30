<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuildingLease extends Model
{
  protected $table = 'building_leases';

  public static function areTheyLeasingThis($buildingName, $userID){
    $buildingType = \App\BuildingTypes::fetchByName($buildingName);
    return \App\BuildingLease::where('userID', $userID)
      ->where('active', 1)->where('buildingTypeID', $buildingType->id)
      ->count() > 0;    
  }

  public static function bad($contractID, $why){
    $contract = \App\Contracts::find($contractID);
    $buildingLeases = \App\BuildingLease::where('contractID', $contractID)->where('active', 1)->get();
    foreach($buildingLeases as $buildingLease){
      $building = \App\Buildings::find($buildingLease->buildingID);
      $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
      $buildingLease->active = false;
      $buildingLease->save();
      \App\History::new($buildingLease->userID, 'lease', "Your lease for " . $buildingType->name . " was cancelled because " . $why);
    }
    $contract->active = false;
    $contract->save();
    \App\History::new($contract->userID, 'lease',  "Your contract to lease out " . $contract->buildingName . " was cancelled because " . $why);

  }

  public static function fetch(){
    return \App\BuildingLease::where('userID', \Auth::id())
      ->where('active', 1)->get();
  }

  public static function new($contractID, $buildingID){
    $buildingLease = new \App\BuildingLease;
    $buildingLease->contractID = $contractID;
    $buildingLease->buildingID = $buildingID;
    $buildingLease->userID = \Auth::id();
    $buildingLease->save();
  }

  public static function use($buildingName, $userID){
    $buildingLeases
      = \App\BuildingLease::where('userID', $userID)->where('active', 1)->get();
    $leasor = \App\User::find($userID);
    foreach ($buildingLeases as $buildingLease){
      $building = \App\Buildings::find($buildingLease->buildingID);
      $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
      if ($buildingType->name != $buildingName){
        continue;
      }
      $contract = \App\Contracts::find($buildingLease->contractID);
      $contractor = \App\User::find($contract->userID);
      if ($leasor->clacks < $contract->price){
        $buildingLease->active = false;
        $buildingLease->save();
        $status = "Your lease for " . $contract->buildingName . " was cancelled because you ran out of money.";
        \App\History::new($buildingLease->userID,  'lease', $status);
        return $status;
      } else if ($building->uses < 1){
        \App\BuildingLease::bad($contract->id, " building is no longer usable.");
        return "This building you're leasing cannot be used anymore. Sorry.";
      }
      $leasor->clacks -= $contract->price;
      $leasor->save();
      $contractor->clacks += $contract->price;
      $contractor->save();
      $building->uses--;
      $building->save();
      return " You used the " . $buildingType->name . " that you're leasing from "
        . $contractor->name . " for " . $contract->price . " clack(s).";
    }
  }
}
