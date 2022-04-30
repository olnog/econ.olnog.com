<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTypes extends Model
{
  protected $table = 'itemTypes';

  public static function new($itemTypeID){
    \App\BuyOrders::new($itemTypeID);
    foreach (\App\User::all() as $user){
      \App\Items::new($itemTypeID, $user->id);
    }
  }
}
