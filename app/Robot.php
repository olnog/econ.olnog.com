<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    protected $table ='robots';

    public static function areTheyEquippedWith($equipmentName, $robotID){
      $robot = \App\Robot::find($robotID);
      if ($robot->equipped == null){
        return false;
      }
      $equipment = \App\Equipment::find($robot->equipped);
      $itemType = \App\ItemTypes::find($equipment->itemTypeID);
      return $itemType->name == $equipmentName;
    }

    public static function fetch(){
      return \App\Robot::join('skillTypes', 'robots.skillTypeID', 'skillTypes.id')
        ->select('robots.id', 'name', 'skillTypeID', 'uses', 'num', 'defaultAction', 'doDefaultWhenAble')->where('userID', \Auth::id())->get();
    }

    public static function fetchBannedActions(){
      return [
        'build', 'make-book', 'repair'
      ];

    }

    public static function new($actionTypeID, $userID){
      $robot = new \App\Robot;
      $robot->actionTypeID = $actionTypeID;
      $robot->userID = $userID;
      $robot->num = count(\App\Robot::fetch()) + 1;
      $robot->save();
    }

    public static function processActions($arr){
      $bannedActions = \App\Robot::fetchBannedActions();
      $status = [];
      $electricity = \App\Items::fetchByName('Electricity', \Auth::id());


      foreach($arr as $shit){
        $robotID = $shit->id;
        $robot = \App\Robot::find($robotID);
        $defaultAction = $shit->defaultAction;
        $skillType = \App\SkillTypes::find($robot->skillTypeID);
        if ($electricity->quantity < 100){
          $status [$robotID] = ['error' => "You don't have enough Electricity to operate this Robot."];
          continue;
        } else if (in_array($defaultAction, $bannedActions) || \App\Actions::list()[$defaultAction] != $skillType->identifier){
          $status [$robotID] = ['error' => "This is not a valid action for this robot."];
          continue;
        }
        $robot->defaultAction = $defaultAction;

        $msg = \App\Actions::do($defaultAction, \Auth::id(), \Auth::id(),
          $robotID);
        if (isset($msg['status'])){
          $robot->uses--;
          $electricity->quantity -= 100;
          $electricity->save();
        }
        $status[$robotID] = $msg;
        $robot->save();

      }
      echo json_encode([
        'statusArr' => $status,
        'robots'    => \App\Robot::fetch(),
        'actions' => \App\Actions::available(),
        'buildingSlots' => \App\User::find(\Auth::id())->buildingSlots,
        'buildings' => \App\Buildings::fetch(),
        'clacks' => \App\User::find(\Auth::id())->clacks,
        'labor' => \App\Labor::fetch(),
        'equipment' => \App\Equipment::fetch(),
        'history' => \App\History::fetch(),
        'csrf' => csrf_token(),
        'items' => Items::fetch(),
        'land' => \App\Land::fetch(),
        'numOfItems' => \App\Items::fetchTotalQuantity(\Auth::id()),
        'itemCapacity' => \App\User::find(\Auth::id())->itemCapacity,
      ]);
    }
    public static function useEquipped($robotID){
      $robot = \App\Robot::find($robotID);
      if ($robot->equipped == null){
        return false;
      }
      $selfCaption = " Robot #" + $robot->num + "'s' ";
      $equipment = Equipment::find($robot->equipped);
      $itemType = ItemTypes::find($equipment->itemTypeID);
      $equipment->uses--;
      $equipment->save();
      if ($equipment->uses <= 0){
        $status = $selfCaption . $itemType->name . " was destroyed in the process.";
        Equipment::destroy($labor->equipped);
        $robot->equipped = null;
        $robot->save();
        return $status;
      }
      return $selfCaption . $itemType->name . " is now at "
        . number_format($equipment->uses / $equipment->totalUses * 100, 2 ) . "%. ";
    }
}
