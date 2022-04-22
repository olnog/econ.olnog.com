<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class History extends Model
{
  protected $table = 'histories';

  public static function fetch(){
    return \App\History::where('userID', Auth::id())
      ->orderBy('created_at', 'desc')->limit(100)->get();
  }

  public static function new($userID, $type, $status){
    $history = new \App\History;
    $history->userID = $userID;
    $history->type = $type;
    $history->status = $status;
    $history->save();
  }
}
