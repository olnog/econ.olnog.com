<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use \App\Land;

class Land extends Model
{
    protected $table = 'land';

    public static function aretheySellingThis($landID){
      return \App\Contracts::where('active', 1)->where('category', 'sellLand')->where('landID', $landID)->first();
    }

    public static function autoBribe(){
      $users = \App\User::all();
      foreach($users as $user){
        if ($user->autoBribe > 0){
          \App\Land::payAutoBribe($user->id);
        }
      }
    }


    public static function averageBribe(){
      if (ceil(\App\Land::avg('bribe')) == 0){
        return 1;
      }
      return ceil(\App\Land::avg('bribe'));
    }

    public static function canTheyTakeFromLease($resource, $userID, $quantity){
      //going to add this later, because leases aren't tied to a specific land
      //and generally grabbing from any land type seems like a bad idea
      $landTypeArr = ['Stone'=> 'mountains', 'Iron Ore'=>'mountains',
        'Coal'=>'mountains', 'Copper Ore'=>'mountains', 'Sand'=>'desert',
        'Uranium Ore'=>'mountains', 'Logs'=>'forest'];
      $error = ['error' => "You don't have enough resources."];
      if ($resource != 'Oil'){
        $lease = \App\Lease::where('active', 1)->where('userID', $userID)
          ->where('landType', $landTypeArr[$resource])->first();
        if ($lease == null){

          return $error;
        }
        $contract = \App\Contracts::find($lease->contractID);
        return \App\Land::takeResource($resource, $contract->userID, $quantity, false);
      }
      $leases = \App\Lease::where('active', 1)->where('userID', $userID)->get();
      foreach($leases as $lease){
        $contract = \App\Contracts::find($lease->contractID);
        $land = \App\Land::where('userID', $contract->userID)->where('oil', '>', $quantity)->first();
        if ($land == null){
          continue;
        }
        \App\History::new($userID, 'lease', "Because of your lease, someone took "
          . $quantity . " " . $resource . " from Parcel #" . $land->id);
        $land->oil -= $quantity;
        $land->save();
        return true;
      }
      return $error;
    }


    public static function checkBribes(){
      \App\Land::autoBribe();
      $land = \App\Land::all();
      $averageBribe = \App\Land::averageBribe();
      $numOfUnprotectedParcels = 0;
      foreach($land as $parcel){
        $parcel->protected = false;
        if ($parcel->bribe >= $averageBribe && $parcel->hostileTakeoverBy == null){
          $parcel->protected = true;
        } else {
          $numOfUnprotectedParcels++;
        }
        $parcel->bribe = 0;
        $parcel->save();
      }
      $chat = new \App\Chat;
      $chat->message = "The State has evaluated all bribes from land owners. "
        . $numOfUnprotectedParcels . " parcels were unprotected.";
      $chat->save();
    }

    public static function count($type, $userID){
      $count = \App\Land::where('userID', $userID)->where('type', $type)->count();
      if ($count < 1){
        return 0;
      }
      $n = 0;
      $i = 1;

      while ($count > 0){
        if ($count / $i > 1){
          $count -= $i;
          $n++;
          $i++;
        } else {
          $count = 0;
        }
      }
      return $n;
    }

    public static function fetch(){
      return Land::join('users', 'land.userID', 'users.id')
        ->select('land.id', 'land.created_at', 'type', 'userID', 'protected',
        'hostileTakeoverBy', 'name', 'bribe', 'valuation', 'stone', 'iron',
        'coal', 'copper', 'oil', 'sand', 'uranium', 'logs', 'depleted')->get();
    }

    public static function fetchMine(){
      return Land::where('userID', Auth::id())->get();
    }

    public static function canTheyGetRidOfThisLand($landID){
      $land = \App\Land::find($landID);
      $user = \App\User::find($land->userID);
      return $user->buildingSlots - \App\Land::fetchBuildingSlots($land->type) > 0;
    }

    public static function doTheyHaveAccessTo($landType){
      if (\App\Lease::areTheyAlreadyLeasing($landType, \Auth::id())){
        return true;
      }
      $land = Land::fetchMine();
      foreach ($land as $landPiece){
        if ($landPiece->type == $landType){
          return true;
        }
      }
      return false;
    }

    public static function doTheyOwn($landType, $userID){
      $land = Land::where('userID', $userID)->where('type', $landType)->get();
      return count($land) > 0;
    }

    public static function fetchBuildingSlots($landType){
      $buildingSlots = [
        'plains' => 5, 'forest' => 3, 'mountains' => 2, 'jungle' => 2, 'desert' => 2
      ];
      return $buildingSlots[$landType];
    }

    static public function integrityCheck($userID){
      $theirLand = \App\Land::where('userID', $userID)->get();
      $buildingType = \App\BuildingTypes::fetchByName('Warehouse');
      $warehouses = \App\Buildings::where('userID', $userID)->where('buildingTypeID', $buildingType->id)->get();
      $newBuildingSlots = 0;
      $newItemCapacity = 1000;

      foreach($theirLand as $parcel){
        $newBuildingSlots += \App\Land::fetchBuildingSlots($parcel->type);
        $newItemCapacity += 1000;
      }
      $newItemCapacity += count($warehouses) * 10000;
      $user = \App\User::find($userID);
      $user->itemCapacity = $newItemCapacity;
      $user->buildingSlots = $newBuildingSlots;
      $user->save();
    }

    static public function isThereAHostileTakeover(){
      $land = \App\Land::fetchMine();
      foreach($land as $parcel){
        if ($parcel->hostileTakeoverBy > 0){
          return true;
        }
      }
      return false;
    }

    static public function new($userID){
      $land = new Land;
      $land->protected = true;
      $land->userID = $userID;
      $landTypeChance = rand(1, 10);
      $user = \App\User::find($userID);
      if ($landTypeChance <= 3){
        $land->type = 'plains';
      } else if ($landTypeChance == 4){
        $land->type = 'desert';
      } else if ($landTypeChance >= 8 && $landTypeChance <= 9){
        $land->type = 'mountains';
      } else if ($landTypeChance >= 5 && $landTypeChance <= 7){
        $land->type = 'forest';
      } else if ($landTypeChance == 10){
        $land->type = 'jungle';
      }
      $user->buildingSlots += \App\Land::fetchBuildingSlots($land->type);
      $user->itemCapacity+= 1000;
      $land->save();
      $user->save();
      return ucfirst($land->type);
    }

    static public function payAllBribes($amount){
      $land = \App\Land::fetchMine();
      $user = \App\User::find(Auth::id());
      if (count($land) == 0 ){
        return ['error' => "You don't own any land."];
      } else if (count($land) * $amount > $user->clacks ){
        return ['error' => "You don't have enough money to pay all your bribes like this."];
      }
      foreach($land as $parcel){
        $parcel->bribe += $amount;
        $parcel->valuation += $amount;
        $parcel->save();

      }
      $user->clacks -= count($land) * $amount;
      $user->save();
      return ['status' => "You paid " . (count($land) * $amount)
        . " clacks across your " . count($land)
        . " parcel(s) to increase your bribe by " . $amount];
    }

    static public function payAutoBribe($userID){
      $user = \App\User::find($userID);
      $land = \App\Land::where('userID', $user->id)->get();
      $i = 0;
      foreach($land as $parcel){
        if ($parcel->bribe < $user->autoBribe){

          if ($user->clacks < $user->autoBribe){
            $user->autoBribe = 0;
            $user->save();
            \App\History::new($user->id, 'land', "You ran out of money paying bribes, so your auto bribe setting was set to 0.");
            return;
          }
          $i++;
          $user->clacks -= ($user->autoBribe - $parcel->bribe);
          $parcel->valuation += ($user->autoBribe - $parcel->bribe);
          $parcel->bribe += ($user->autoBribe - $parcel->bribe);
          $user->save();
          $parcel->save();
          \App\History::new($user->id, 'land', " Parcel #" . $parcel->id
            . " is now worth " . $parcel->valuation
            . " and has a total bribe worth " . $parcel->bribe . ".");
        }
      }
      \App\History::new($user->id, 'land', " You auto-paid bribes on " . $i . " parcels that you own.");
    }

    public static function takeResource($resource, $userID, $quantity, $useLease){

      $dbNameArr = ['Stone' => 'stone', 'Iron Ore'=> 'iron', 'Coal'=>'coal',
        'Copper Ore'=>'copper', 'Oil'=>'oil', "Sand"=>'sand',
        'Uranium Ore'=>'uranium', 'Logs'=>'logs'];
      $landTypeArr = ['Stone'=> 'mountains', 'Iron Ore'=>'mountains',
        'Coal'=>'mountains', 'Copper Ore'=>'mountains', 'Sand'=>'desert',
        'Uranium Ore'=>'mountains', 'Logs'=>'forest'];

      $land = \App\Land::where('userID', $userID)->where('oil', '>', $quantity)->first();
      if ($resource != 'Oil'){
        $land = \App\Land::where('userID', $userID)
        ->where($dbNameArr[$resource], '>', 0)->first();
      }
      if ($land == null  || $land[$dbNameArr[$resource]] < 1){
        if (!$useLease){
          return ['error' => "You don't have enough resources."];
        }
        return \App\Land::canTheyTakeFromLease($resource, $userID, $quantity);
      }
      if (!$useLease){
        \App\History::new($userID, 'lease', "Because of your lease, someone took "
          . $quantity . " " . $resource . " from Parcel #" . $land->id);
      }
      $land[$dbNameArr[$resource]] -= $quantity;
      $land->save();
      return true;
    }
}
