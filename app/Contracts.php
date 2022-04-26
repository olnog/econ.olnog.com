<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contracts extends Model
{
  protected $table = 'contracts';
  
  public static function anyoneBuying($contractID){
    $sellContract = \App\Contracts::find($contractID);
    $buyContracts = \App\Contracts::where('itemTypeID', $sellContract->itemTypeID)
      ->where('active', 1)->where('userID', '!=', $sellContract->userID)
      ->where('price', '>=', $sellContract->price)->where('category', 'buyOrder')
      ->get();
    $i = 0;
    $money = 0;
    foreach ($buyContracts as $buyContract){
      $buying = true;
      while ($buying){
        $buying = \App\Contracts::buyFromSellContract($sellContract, $buyContract);
        $money += $buyContract->price;
        $i++;
      }
    }
    if ($i > 0){
      return " People were already buying this item, so you sold " . $i
        . " items and earned " . $money . " clack(s)";
    }
    return "";
  }


  public static function anyoneSelling($contractID){
    $buyContract = \App\Contracts::find($contractID);
    $sellContracts = \App\Contracts::where('itemTypeID', $buyContract->itemTypeID)
      ->where('active', 1)->where('userID', '!=', $buyContract->userID)
      ->where('price', '<=', $buyContract->price)->where('category', 'sellOrder')
      ->get();
    $i = 0;
    $money = 0;
    foreach ($sellContracts as $sellContract){
      $buying = true;
      while ($buying){
        $buying = \App\Contracts::buyFromSellContract($sellContract, $buyContract);
        $money += $buyContract->price;
        $i++;
      }
    }
    if ($i > 0){
      return " People were already selling this item, so you bought it " . $i
        . " times and earned " . $money . " clack(s)";
    }
    return "";
  }

  public static function buyFromSellContract($sellContract, $buyContract){
    $seller = \App\User::find($sellContract->userID);
    $sellerItem = \App\Items::where('itemTypeID', $sellContract->itemTypeID)
      ->where('userID', $seller->id)->first();
    $buyer = \App\User::find($buyContract->userID);
    $buyerItem = \App\Items::where('itemTypeID', $buyContract->itemTypeID)
      ->where('userID', $buyer->id)->first();
    if ($sellerItem->quantity < 1){
      $sellContract->active = false;
      $sellContract->save();
      \App\History::new($buyer->id, 'contracts', 'You ran out of items to sell so your sell contract was cancelled.');
      return false;
    } else if ($buyer->clacks < $sellContract->price){
      if ($buyer->clacks < $buyContract->price){
        $buyContract->active = false;
        $buyContract->save();
        \App\History::new($buyer->id, 'contracts', 'You ran out of clacks to buy so your buy contract was cancelled.');
      }
      return false;
    }
    $seller->clacks += $buyContract->price;
    $seller->save();
    $buyer->clacks -= $buyContract->price;
    $buyer->save();
    $sellerItem->quantity--;
    $sellerItem->save();
    $buyerItem->quantity++;
    $buyerItem->save();
    if ($sellerItem->quantity < 1){
      $sellContract->active = false;
      \App\History::new($buyer->id, 'contracts', 'You ran out of items to sell so your sell contract was cancelled.');
    } else if ($buyer->clacks < $sellContract->price){
      if ($buyer->clacks < $buyContract->price){
        $buyContract->active = false;
        \App\History::new($buyer->id, 'contracts', 'You ran out of clacks to buy so your buy contract was cancelled.');
      }
    }
    if ($sellContract->active && $sellContract->until == 'sold'){
      $sellContract->conditionFulfilled++;
      if ($sellContract->conditionFulfilled >= $sellContract->condition){
        $sellContract->active = false;
        \App\History::new($seller->id, 'contracts', "You sold "
        . $sellContract->conditionFulfilled . " of " . $sellContract->condition
        . " items so your sell contract was cancelled.");
      }
    }
    if ($buyContract->active && $buyContract->until == 'bought'){
      $buyContract->conditionFulfilled++;
      if ($buyContract->conditionFulfilled >= $buyContract->condition){
        $buyContract->active = false;
        \App\History::new($buyer->id, 'contracts', "You bought "
        . $buyContract->conditionFulfilled . " of " . $buyContract->condition
        . " items so your buy contract was cancelled.");
      }
    } else if ($buyContract->active && $buyContract->until == 'inventory'
      && $buyerItem->quantity >= $buyContract->condition){
      $buyContract->active = false;
      \App\History::new($buyer->id, 'contracts', "You now have a total of "
        . $buyContract->condition . " items so your buy contract was cancelled.");
    }
    $buyContract->save();
    $sellContract->save();
    if (!$buyContract->active || !$buyContract->active){
      return false;
    }
    return true;
  }

  public static function doTheyAlreadyHaveSimilarContract($category, $itemTypeID){

    //return $contract != null;
  }

  public static function fetch(){
    return \App\Contracts
      ::join('users', 'contracts.userID', 'users.id')
      ->select('contracts.id', 'category', 'itemTypeID', 'price', 'until',
      'userID', 'condition', 'conditionFulfilled', 'users.name as username',
      'buildingTypeID', 'buildingID', 'action', 'minSkillLevel', 'landID', 'landType', 'buildingName')
      ->where('active', true)->orderBy('contracts.created_at', 'desc')->get();
  }
}
