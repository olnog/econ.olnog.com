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

  public static function new($itemTypeID){
    $buyOrder = new \App\BuyOrders;
    $buyOrder->itemTypeID = $itemTypeID;
    $buyOrder->save();
  }
}
