<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bids extends Model
{
  protected $table = 'bids';

  public static function bid($landID, $amount){
    $land = \App\Land::find($landID);
    $user = \App\User::find(\Auth::id());
    $lastBid = \App\Bids::lastBid($landID);
    if ($amount < ceil($lastBid->amount * 1.1)){
      return ['error' => "This is too low of a bid. Each bid must be 10% higher than the last one."];
    } else if ($user->clacks < $amount){
      return ['error' => "You don't have enough clacks to put this bid in."];
    } else if ($lastBid->userID == \Auth::id()){
      return ['error' => "You've already bid on this."];
    } else if ($land->hostileTakeoverBy == null){
      return ['error' => "There is no hostile takeover to put a bid on. Sorry."];
    } else if ($land->userID != \Auth::id()
      && $land->hostileTakeoverBy != \Auth::id()){
      return ['error' => "You've not able to bid on this."];
    }
    $numOfPreviousBids = \App\Bids::where('userID', \Auth::id())
      ->where('landID', $landID)
      ->where('hostileTakeoverNum', $land->hostileTakeoverNum)->count();
    $user->clacks -= $amount;
    $user->save();
    $bid = new \App\Bids;
    $bid->userID = \Auth::id();
    $bid->landID = $landID;
    $bid->hostileTakeoverNum = $land->hostileTakeoverNum;
    $bid->amount = $amount;
    $bid->bidNum = $numOfPreviousBids + 1;
    $bid->save();
    return ['status' => "You placed a bid of " . $amount . " on parcel #"
      . $landID . "."];
  }

  public static function checkBids(){
    $land = \App\Land::where('hostileTakeoverBy', '>', 0)->get();
    foreach ($land as $parcel){
      $lastBid = \App\Bids::lastBid($parcel->id);
      if (strtotime('now') - strtotime($lastBid->created_at) > 24 * 60 * 60){
        \App\Bids::completeHostileTakeover($parcel->id);
      }
    }
  }

  public static function completeHostileTakeover($landID){
    $land = \App\Land::find($landID);
    $lastBid = \App\Bids::lastBid($landID);
    $owner = \App\User::find($land->userID);
    $attacker = \App\User::find($land->hostileTakeoverBy);
    $allOfOwnersBids = \App\Bids::where('userID', $land->userID)
      ->where('landID', $landID)
      ->where('hostileTakeoverNum', $land->hostileTakeoverNum)->sum('amount');
    $allOfAttackersBids = \App\Bids::where('userID', $land->hostileTakeoverBy)
      ->where('landID', $landID)
      ->where('hostileTakeoverNum', $land->hostileTakeoverNum)->sum('amount');
    $ownersFirstBid = 0;
    if ($allOfOwnersBids != 0){
      $ownersFirstBid = \App\Bids::where('userID', $land->userID)
        ->where('hostileTakeoverNum', $land->hostileTakeoverNum)
        ->where('landID', $landID)->where('bidNum', 1)->first()->amount;
    }
    if ($lastBid->userID == $land->userID){ // owner is winner
      $owner->clacks += ($allOfOwnersBids - $ownersFirstBid );
      \App\History::new($owner->id, 'bid',
        "You maintained ownership over parcel #" . $landID
        . " and you got back " . ($allOfOwnersBids - $ownersFirstBid )
        . " clacks.");
      \App\History::new($attacker->id, 'bid',
        "You lost the hostile takeover of parcel #" . $landID);
    } else if ($lastBid->userID == $land->hostileTakeoverBy){
      \App\History::new($owner->id, 'bid',
        "You lost ownership of parcel #" . $landID . " but you got back "
        . $ownersFirstBid . " clacks.");
      \App\History::new($attacker->id, 'bid',
        "You won the hostile takeover of parcel #" . $landID . ". Congrats!");
      $owner->clacks += $ownersFirstBid ;
      $attacker->save();
      $land->userID = $land->hostileTakeoverBy;
    }
    \App\Land::integrityCheck($attacker->id);
    \App\Land::integrityCheck($owner->id);
    $owner->save();
    $land->protected = 1;
    $land->hostileTakeoverBy = null;
    $land->changedOwnerAt = date('Y-m-d H:i:s');
    $land->valuation += $allOfOwnersBids + $allOfAttackersBids;
    $land->save();
  }


  public static function hostileTakeover($landID, $amount){
    $land = \App\Land::find($landID);
    $user = \App\User::find(\Auth::id());
    if ($amount < $land->valuation * 2){
      return ['error' => "This is too low of a bid for this land's valuation."];
    } else if ($user->clacks < $amount){
      return ['error' => "You don't have enough clacks to put this bid in."];
    } else if ($land->hostileTakeoverBy != null){
      return ['error' => "This parcel is already undergoing a hostile takeover. Sorry."];
    } else if ($land->userID == \Auth::id()){
      return ['error' => "You can't do a hostile takeover on your own parcel. Sorry."];
    }
    $land->hostileTakeoverBy = \Auth::id();
    $land->hostileTakeoverNum ++;
    $land->save();
    $user->clacks -= $amount;
    $user->save();
    $bid = new \App\Bids;
    $bid->bidNum = 1;
    $bid->userID = \Auth::id();
    $bid->landID = $landID;
    $bid->amount = $amount;
    $bid->hostileTakeoverNum = $land->hostileTakeoverNum;
    $bid->save();
    return ['status' => "You spent " . number_format($amount)
      . " clacks and started a hostile takeover on parcel #" . $landID
      . ". You now have " . number_format($user->clacks) . "."];
  }

  public static function lastBid($landID){
    return \App\Bids::where('landID', $landID)->orderBy('created_at', 'desc')
      ->first();
  }
}
