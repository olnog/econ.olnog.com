<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Items extends Model
{
  protected $table = 'items';

  static public function fetchByName($name, $userID){
    $itemType = \App\ItemTypes::where('name', $name)->first();
    return \App\Items::where('itemTypeID', $itemType->id)->where('userID', $userID)->first();
  }

  static public function fetchByItemTypeID($itemTypeID){
    return \App\Items::where('userID', Auth::id())->where('itemTypeID', $itemTypeID)->first();
  }

  static public function fetch(){
    return Items::where('userID', Auth::id())
      ->join('itemTypes', 'items.itemTypeID', 'itemTypes.id')
      ->select('items.id', 'itemTypeID', 'quantity', 'name', 'description',
        'durability', 'material')
      ->orderBy('name')->get();
  }

  static public function fetchInventory(){
    return Items::where('userID', Auth::id())
      ->join('itemTypes', 'items.itemTypeID', 'itemTypes.id')
      ->where('quantity', '>', 0 )
      ->select('items.id', 'itemTypeID', 'quantity', 'name', 'description',
        'durability', 'material')
      ->orderBy('name')->get();
  }

  static public function fetchTotalQuantity($userID){
    $items = \App\Items::where('userID', $userID)->where('countable', true)->get();
    $total = 0;
    foreach ($items as $item){
      $total += $item->quantity;
    }
    return $total;
  }

  static public function doTheyHave($name, $quantity){
    $item = Items::fetchByName($name, Auth::id());
    if ($item->quantity >= $quantity){
      return true;
    }
    return false;
  }


  public function type(){
    return $this->hasOne('App\ItemTypes', 'itemTypesID');
  }
}
