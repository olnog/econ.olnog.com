<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    protected $table ='robots';

    public static function fetch(){
      return \App\Robot::join('action_types', 'robots.actionTypeID',
        'action_types.id')->select('robots.id', 'name', 'actionTypeID',
        'uses', 'num', 'defaultAction', 'doDefaultWhenAble')
        ->where('userID', \Auth::id())->get();
    }



    public static function new($actionTypeID, $userID){
      $robot = new \App\Robot;
      $robot->actionTypeID = $actionTypeID;
      $robot->userID = $userID;
      $robot->num = count(\App\Robot::fetch()) + 1;
      $robot->save();
    }

    public static function processActions($robots){
      $bannedActions = \App\Actions::fetchBanned();
      $status = [];
      $electricity = \App\Items::fetchByName('Electricity', \Auth::id());
      foreach($robots as $robotBeingProcessed){
        $robotID = $robotBeingProcessed->id;
        $robot = \App\Robot::find($robotID);
        $actionType = \App\ActionTypes::find($robot->actionTypeID);
        if ($electricity->quantity < 100){
          $status [$robotID]
            = ['error'
              => "You don't have enough Electricity to operate this Robot."];
          continue;
        } else if (in_array($actionType->name, $bannedActions)){
          $status [$robotID]
            = ['error' => "This is not a valid action for this robot."];
          continue;
        }
        $msg = \App\Actions::do($actionType->name, \Auth::id(), \Auth::id(),
          $robotID, false);
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
        'csrf'      => csrf_token(),
        'info'      => \App\User::fetchInfo(),
      ]);
    }
}
