<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

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

    

    public static function fetchInfo(){
      $labor = \App\Labor::fetch();
      $user = \App\User::find(\Auth::id());
      $numOfParcels = \App\Land::where('userID', \Auth::id())->count();
      $numOfActions = $labor->availableSkillPoints + $labor->allocatedSkillPoints;
      $numOfItems = \App\Items::where('userID', \Auth::id())->sum('quantity');
      $numOfBuildings = \App\Buildings::where('userID', \Auth::id())->count();
      $numOfContracts = \App\Contracts::where('userID', \Auth::id())
        ->where('active', 1)->count();
      return [
        'buildingSlots'   => $user->buildingSlots,
        'clacks'          => $user->clacks,
        'numOfActions'    => $numOfActions,
        'numOfBuildings'  => $numOfBuildings,
        'numOfContracts'  => $numOfContracts,
        'numOfItems'      => intval($numOfItems),
        'numOfParcels'    => $numOfParcels,
        'numOfUnlocked'   => count(\App\Actions::fetchUnlocked(\Auth::id(), true)),
        'settings'        => [
                              'sound'=>$user->soundSetting,
                              'eatFood'=>$user->eatFoodSetting,
                              'useHerbMeds' => $user->useHerbMedsSetting,
                              'useBioMeds' => $user->useBioMedsSetting,
                              'useNanoMeds' => $user->useNanoMedsSetting,
                            ],
        'username'        => $user->name,
      ];
    }

    public static function register($userID){
      $labor = new Labor;
      $labor->userID = $userID;
      $labor->save();

      $actionTypes = \App\ActionTypes::all();
      foreach($actionTypes as $actionType){
        $action = new \App\Actions;
        $action->actionTypeID = $actionType->id;
        $action->userID = $userID;
        $action->save();
      }
      $itemTypes = \App\ItemTypes::all();
      foreach($itemTypes as $itemType){
        $item = new \App\Items;
        $item->itemTypeID = $itemType->id;
        $item->userID = $userID;
        $item->save();
      }
    }

    public static function reset(){
      $buildingLeases = \App\BuildingLease::fetch();
      foreach($buildingLeases as $buildingLease){
        \App\BuildingLease::destroy($buildingLease->id);
      }

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
      $labor->actions = 0;
      $labor->actionsUntilSkill = 30;
      $labor->rebirth = false;
      $labor->legacy = null;
      $labor->save();

      $robots = \App\Robot::fetch();
      foreach($robots as $robot){
        \App\Robot::destroy($robot->id);
      }

      \App\Actions::reset();

      $user = Auth::user();
      $user->buildingSlots = 0;
      $user->save();

      \App\History::new(Auth::id(), 'reset', "Reset!");
    }

    public static function resetAll(){
      $users = \App\User::all();
      foreach($users as $user){
        $user->buildingSlots = 0;
        $user->clacks = 0;
        $user->minutes = 0;
        $user->save();
      }
      $labors = \App\Labor::all();
      foreach($labors as $labor){
        $labor->equipped = null;
        $labor->availableSkillPoints = 4;
        $labor->allocatedSkillPoints = 0;
        $labor->actions = 0;
        $labor->actionsUntilSkill = 30;
        $labor->rebirth = false;
        $labor->legacy = null;
        $labor->escrow = 0;
        $labor->alsoEquipped = null;
        $labor->save();
      }
    }
}
