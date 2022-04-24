<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    public static function fetchRemainingItemCapacity($userID){
      return \App\User::find($userID)->itemCapacity - \App\Items::fetchTotalQuantity($userID);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function reset(){
      $leases = \App\Lease::fetch();
      foreach($leases as $lease){
        \App\Lease::destroy($lease->id);
      }

      $buildings = \App\Buildings::fetch()['built'];
      foreach ($buildings as $building){
        \App\Buildings::destroy($building->id);
      }

      $land = \App\Land::fetchMine();
      foreach($land as $parcel){
        \App\Land::destroy($parcel->id);
      }

      $items = \App\Items::fetch();
      foreach ($items as $item){
        $item->quantity = 0;
        $item->save();
      }



      $equipment = \App\Equipment::fetch();
      foreach($equipment as $piece){
        \App\Equipment::destroy($piece->id);

      }

      $contracts = \App\Contracts::where('userID', Auth::id())->where('active', 1)->get();
      foreach($contracts as $contract){
        \App\Contracts::destroy($contract->id);
      }

      $labor = \App\Labor::fetch();

      $labor->equipped = null;
      $labor->availableSkillPoints = 4;
      $labor->allocatedSkillPoints = 0;
      $labor->actions=0;
      $labor->actionsUntilSkill = 30;
      $labor->rebirth=false;
      $labor->legacy = null;
      $labor->legacySkillTypeID = null;
      $labor->save();

      $robots = \App\Robot::fetch();
      foreach($robots as $robot){
        \App\Robot::destroy($robot->id);
      }
      \App\Actions::reset();
      \App\Skills::reset();

      $user = Auth::user();
      $user->buildingSlots = 0;
      $user->itemCapacity = 1000;
      $user->save();

      \App\History::new(Auth::id(), 'reset', "You did a reset. Everything is gone now.");
    }
}
