<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
  protected $table = 'chats';

  public static function fetch(){
    return \App\Chat::join('users', 'chats.userID', 'users.id')
      ->select('chats.id', 'userID', 'name', 'message', 'chats.created_at as when')->get();
  }

  public static function new($userID, $msg){
    $chat = new \App\Chat;
    $chat->message = $msg;
    $chat->userID = $userID;
    $chat->save();    
  }
}
