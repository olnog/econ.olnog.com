<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTypes extends Model
{
  protected $table = 'itemTypes';

  public static function durability($skillRank){
    $durabilityCaption = [null, 'horrible', 'poor', 'average', 'good', 'great'];
    return $durabilityCaption[$skillRank];
  }

  public static function new($itemTypeID){
    $buyOrder = new \App\BuyOrders;
    $buyOrder->itemTypeID = $itemTypeID;
    $buyOrder->unitCost = 1;
    $buyOrder->save();
    $itemType = \App\ItemTypes::find($itemTypeID);
    foreach (\App\User::all() as $user){
      $item = new \App\Items;
      $item->itemTypeID = $itemTypeID;
      //when oil was inserted, it was listed as uncountable
      $item->countable =$itemType->countable;
      $item->userID = $user->id;
      $item->save();

    }
  }
}
