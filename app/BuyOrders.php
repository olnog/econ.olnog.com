<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuyOrders extends Model
{
  protected $table = 'buyOrders';

  public static function check(){
    $buyOrders = \App\BuyOrders::where('active', 1)->get();
    foreach($buyOrders as $buyOrder){
      if(time() - strtotime($buyOrder->updated_at) > 86400){
        $itemType = \App\ItemTypes::find($buyOrder->itemTypeID);
        $caption = $itemType->name;
        if ($itemType->material != null){
          $caption .= " (" . $itemType->material . "/" . $itemType->durability . ")";
        }
        /*
        $chat = new \App\Chat;
        $chat->message = " Because no one has fulfilled it in 24 hours, the buy order for "
          . number_format($buyOrder->quantity) . " " . $caption . " has gone up from "
          . number_format($buyOrder->cost) . " clack(s) to " . number_format(ceil($buyOrder->cost * 1.1)) . " clack(s). ";
        $chat->save();
        */
        $buyOrder->cost = ceil($buyOrder->cost * 1.1);
        $buyOrder->save();
      }
    }
  }

  public static function fetch($sort){
    if ($sort == 'cost'){
      return BuyOrders
        ::join('itemTypes', 'buyOrders.itemTypeID', 'itemTypes.id')
        ->where('active', true)->orderBy('cost', 'desc')
        ->select('buyOrders.id', 'itemTypeID', 'quantity', 'cost',
        'filled_at', 'filledBy', 'name', 'description', 'material',
        'durability', 'buyOrders.updated_at', 'unitCost')->get();
    } else if ($sort == 'unitCost'){
      return BuyOrders
        ::join('itemTypes', 'buyOrders.itemTypeID', 'itemTypes.id')
        ->where('active', true)->orderBy('unitCost', 'desc')
        ->select('buyOrders.id', 'itemTypeID', 'quantity', 'cost',
        'filled_at', 'filledBy', 'name', 'description', 'material',
        'durability', 'buyOrders.updated_at', 'unitCost')->get();
    }
    return BuyOrders
      ::join('itemTypes', 'buyOrders.itemTypeID', 'itemTypes.id')
      ->where('active', true)->orderBy('name')
      ->select('buyOrders.id', 'itemTypeID', 'quantity', 'cost',
      'filled_at', 'filledBy', 'name', 'description', 'material',
      'durability', 'buyOrders.updated_at', 'unitCost')->get();
  }
}
