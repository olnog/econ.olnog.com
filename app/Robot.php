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

      foreach($robots as $robotBeingProcessed){
        $robotID = $robotBeingProcessed->id;
        $robot = \App\Robot::find($robotID);
        $actionType = \App\ActionTypes::find($robot->actionTypeID);
        if (in_array($actionType->name, $bannedActions)){
          $status [$robotID]
            = ['error' => "This is not a valid action for this robot."];
          continue;
        }

        $msg = \App\Actions::do($actionType->name, \Auth::id(), \Auth::id(),
          $robotID, null, false);
        if (isset($msg['status'])){
          $robot->uses--;

        }
        $status[$robotID] = $msg;
        $robot->save();

      }
      echo json_encode([
        'statusArr' => $status,
        'csrf'      => csrf_token(),
        'electricity' => \App\Items::fetchByName('Electricity', \Auth::id())->quantity,
        'info'      => \App\User::fetchInfo(),
      ]);
    }
}
