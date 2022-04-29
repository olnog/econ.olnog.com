<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
  protected $table = 'leases';

  public static function areTheyAlreadyLeasing($landType, $userID){
    $leases = \App\Lease::where('userID', $userID)->where('active', 1)->where('landType', $landType)->get();
    return count($leases) > 0;
  }

  public static function bad($contractID, $reason){
    $leases = \App\Lease::where('contractID', $contractID)->where('active', 1)->get();
    $contract = \App\Contracts::find($contractID);
    $contractor = \App\User::find($contract->userID);
    \App\History::new($contract->userID, 'lease', "All the leases you had for your " . $contract->landType . "  are no longer valid because you " . $reason);
    foreach ($leases as $lease){
      \App\History::new($lease->userID, 'lease', "Your lease with " . $contractor->name . "  for " . $contract->landType . " is no longer valid because they " . $reason);
      $lease->active = false;
      $lease->save();
    }
  }

  public static function fetch(){
    return \App\Lease::where('userID', \Auth::id())->where('active', 1)->get();
  }

  public static function use($landType, $userID){
    $lease = \App\Lease::where('userID', $userID)->where('active', 1)->where('landType', $landType)->first();
    $contract = \App\Contracts::find($lease->contractID);
    $contractor = \App\User::find($contract->userID);
    $leasor = \App\User::find($lease->userID);
    if (!\App\Land::doTheyOwn($lease->landType, $contractor->id)){
      \App\Lease::bad($contract->id, ' no longer have ' . $lease->landType);
      $contract->active = false;
      $contract->save();
      return false;
    } else if ($leasor->clacks < $contract->price){
      \App\History::new($contractor->id, 'lease', $leasor->name . " didn't have enough money, so their lease was canceled." );
      \App\History::new($leasor->id, 'lease', "You didn't have enough money, so your lease was canceled with " . $contractor->name . " for " . $contract->landType);
      $lease->active = false;
      $lease->save();
      return false;
    }
    $contractor->clacks += $contract->price;
    $contractor->save();
    $leasor->clacks -= $contract->price;
    $leasor->save();
    return ucfirst($lease->landType) . ": <span class='fn'>-" . $contract-> price . "</span> Clack(s)";
  }
}
