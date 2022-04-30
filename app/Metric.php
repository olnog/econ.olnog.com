<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
  protected $table = 'metrics';

  public static function newAction($userID, $action){
    $metric = new \App\Metric;
    $metric->userID = $userID;
    $metric->action = $action;
    $metric->save();
  }

  public static function newButton($userID, $button){
    $metric = new \App\Metric;
    $metric->userID = $userID;
    $metric->button = $button;
    $metric->save();
  }

  public static function logAllButtons($userID, $buttons){
    if (empty($buttons)){
      return;
    }
    for ($i = 0; $i < count($buttons); $i++){
      \App\Metric::newButton($userID, $buttons[$i]);
    }

  }

}
