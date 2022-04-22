<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use \App\Labor;
class Labor extends Model
{
    protected $table = 'labor';

    public static function areTheyEquippedWith($itemName, $userID){
      \App\Equipment::doTheyNeedToSwitch($itemName, $userID);
      $labor = Labor::where('userID', $userID)->first();
      if ($labor->equipped == null){
        return false;
      }
      $equipment = Equipment::find($labor->equipped);
      if ($equipment->type->name == $itemName){
        return true;
      }
      return false;
    }

    public static function defaultConsumption($userID){
      $user =\App\User::find($userID);
      return [
        'food' => $user->eatFoodSetting,
        'herbMeds' => $user->useHerbMedsSetting,
        'bioMeds' => $user->useBioMedsSetting,
        'nanoMeds' => $user->useNanoMedsSetting,
      ];


    }

    public static function decrementWorkHours($userID, $consumption, $radiationPoisoning){
      $agentCaption = " they ";
      if ($userID == Auth::id()){
        $agentCaption = " you ";
      }
      $medicated = false;
      $food = Items::fetchByName('Food', $userID);
      $herbMeds = \App\Items::fetchByName('HerbMeds', $userID);
      $bioMeds = \App\Items::fetchByName('BioMeds', $userID);
      $nanoMeds = \App\Items::fetchByName('NanoMeds', $userID);

      $children = Items::fetchByName('Children', $userID);
      $labor = \App\Labor::where('userID', $userID)->first();
      $status = "";
      if ($nanoMeds->quantity > 0 && $consumption['nanoMeds']){
        $nanoMeds->quantity--;
        $nanoMeds->save();
        $medicated = true;
        $status = " Because of your NanoMeds that you took, you didn't use any Work Hours doing this.";
      }
      if ($bioMeds->quantity > 0 && !$medicated && $consumption['bioMeds']){
        $bioMeds->quantity--;
        $bioMeds->save();
        if (rand(1, 5) == 1){
          $medicated = true;
          $status = " Your BioMeds kicked in and you didn't use any Work Hours doing this.";
        }
      }

      if ($herbMeds->quantity > 0 && !$medicated && $consumption['herbMeds']){
        $herbMeds->quantity--;
        $herbMeds->save();
        if (rand(1, 10) == 1){
          $medicated = true;
          $status = " Your HerbMeds kicked in and you didn't use any Work Hours doing this.";
        }
      }
      if ($food->quantity > 0 && $consumption['food']){
        $food->quantity--;
        $food->save();
        if (!$medicated){
          $labor->workHours--;
        }
      } else if ($labor->workHours == 1 && !$medicated){
        $labor->workHours=0;
      } else {
        if (!$medicated){
          $labor->workHours -= 2;
          $status = " Because " . $agentCaption . " were hungry, it took two hours instead of one.";
        }
      }
      if ($children->quantity > 0 ){
        if ($children->quantity > $food->quantity){
          $children->quantity = 0;
          $children->save();
          $status .= " You didn't have enough food to feed your Children, so they all died. Dang, that sucks, sorry.";
        } else {
          $food->quantity -= $children->quantity;
          $food->save();
          if ($children->quantity > 1){
            $status .= " You fed each of your " . $children-> quantity . " children food.";
          } else {
            $status .= " You fed your " . $children-> quantity . " child food.";
          }

        }
      }
      if ($radiationPoisoning && !$medicated){
        $loss = floor($labor->workHours * .1);
        $labor->workHours -= $loss;
        $labor->save();
        $status .= " Because you were not wearing a Radiation Suit, you lost " . $loss .  " Work Hours to radiation..";
      }
      if ($labor->workHours < 1){
        $labor->workHours=0;
        $labor->rebirth = true;
      }
      $labor->actions++;
      if ($labor->actions >= $labor->actionsUntilSkill){
        $labor->actions=0;
        \App\Labor::incrementSkillPoints();
      }

      $labor->save();
      return $status;

    }

    public static function doAction($userID){

      $labor = \App\Labor::where('userID', $userID)->first();
      $labor->actions++;
      if ($labor->actions >= $labor->actionsUntilSkill){
        $labor->actionsUntilSkill *= 1.1;
        $labor->actions=0;
        \App\Labor::incrementSkillPoints($userID);
      }
      $labor->save();
    }

    public static function feedChildren($userID){
      $children = \App\Items::fetchByName('Children', $userID);
      $food = \App\Items::fetchByName('Food', $userID);
      $status = "";
      if ($children->quantity > 0 ){
        if ($children->quantity > $food->quantity){
          $children->quantity = 0;
          $children->save();
          $status .= "You didn't have enough food to feed your Children, so they all died. Dang, that sucks, sorry.";
        } else {
          $food->quantity -= $children->quantity;
          $food->save();
          if ($children->quantity > 1){
            $status .= "You fed each of your " . $children-> quantity . " children food.";
          } else {
            $status .= "You fed your " . $children-> quantity . " child food.";
          }

        }
      }
      return $status;
    }

    public static function fetch(){
      return Labor::where('userID', Auth::id())->first();
    }

    public static function fetchTax(){
      $skill = \App\Skills::fetchByIdentifier('finance', \Auth::id());
      $taxRate = .5;
      return $taxRate - ($taxRate * ($skill->rank * .2));
    }

    public static function fetchWorkHours(){
      return 1000;
    }

    public static function formatConsumption($consumption){
      return [
        'food' => $consumption->food,
        'herbMeds' => $consumption->herbMeds,
        'bioMeds' => $consumption->bioMeds,
        'nanoMeds' => $consumption->nanoMeds,
      ];
    }
    public static function increaseMaxSkillPoints($userID){
      $labor = \App\Labor::where('userID', $userID)->first();
      if ($labor->pendingMaxSkillPoints < 15){
        $labor->pendingMaxSkillPoints++;
        $labor->save();
      }
    }
    public static function incrementSkillPoints($userID){
      $labor = \App\Labor::where('userID', $userID)->first();

      if ($labor->availableSkillPoints >= $labor->maxSkillPoints){
        return \App\Labor::increaseMaxSkillPoints();
      }
      $labor->availableSkillPoints++;
      $labor->save();


    }

    public static function radPenalty($userID){
      $labor = \App\Labor::where('userID', $userID)->first();
      $labor->actionsUntilSkill *= 1.05;
      $labor->save();
    }

    public static function rebirth($genius, $legacy, $legacySkillTypeID, $childProdigy){
      $labor = \App\Labor::fetch();
      $tax = \App\Labor::fetchTax();
      $user = \App\User::find(\Auth::id());
      $estateTax = ceil($tax * $user->clacks);
      $user->clacks -=  $estateTax;
      if ($user->clacks < 0){
        $user->clacks = 0;
      }
      $user->save();
      if (!$labor->rebirth){
        return;
      }
      if ($legacySkillTypeID != null){
        $skillType = \App\SkillTypes::find($legacySkillTypeID);
        $legacySkillRank = \App\Skills::fetchByIdentifier($skillType->identifier, \Auth::id())->rank;
      }
      \App\Skills::reset();
      if ($legacy){
        $children = \App\Items::fetchByName('Children', \Auth::id());
        if ($children->quantity < 1){
          return;
        }
        $children->quantity--;
        $children->save();
        $skill = \App\Skills::fetchByIdentifier($skillType->identifier, \Auth::id());
        $skill->rank = $legacySkillRank;
        $skill->save();
      }
      if ($genius){
        $books = \App\Items::fetchByName('Books', \Auth::id());
        if ($books->quantity < $labor->maxSkillPoints){
          return;
        }
        $books->quantity -= $labor->maxSkillPoints;
        $books->save();
        $labor->maxSkillPoints++;
      }

      if ($childProdigy){
          if ($labor->maxSkillPoints < 25){
            return;
          }
          $labor->maxSkillPoints -= 10;
          $labor->startingSkillPoints++;
      }

      $labor->maxSkillPoints += $labor->pendingMaxSkillPoints;
      $labor->pendingMaxSkillPoints = 0;

      $labor->workHours = $labor->startingWorkHours;
      $labor->availableSkillPoints = $labor->startingSkillPoints;
      $labor->allocatedSkillPoints = 0;
      $labor->actions = 0;
      $labor->actionsUntilSkill = 30;
      $labor->rebirth = false;
      $labor->save();

      $corpse = \App\Items::fetchByName('Corpse', Auth::id());
      $corpse->quantity++;
      $corpse->save();
      \App\History::new(Auth::id(), 'rebirth',
        "You've been reborn but your corpse was left behind as a reminder of "
        . " your past life. You paid " . number_format($estateTax) . " clack(s) in estate tax. "
        . "You now have " . number_format($user->clacks) . " clacks.");
      if ($labor->escrow > 0 ){
        $user->clacks += $labor->escrow;
        $user->save();
        \App\History::new(Auth::id(), 'rebirth',
          "You were paid for your reproduction contract " . number_format($labor->escrow)
          . " clack(s). You now have " . number_format($user->clacks) . " clacks.");
        $labor->escrow = 0;
        $labor->save();


      }
    }

    public static function useMeds($userID){
      $medicated = false;
      $labor = \App\Labor::where('userID', $userID)->first();
      $user = \App\User::find($userID);
      $herbMeds = \App\Items::fetchByName('HerbMeds', $userID);
      $bioMeds = \App\Items::fetchByName('BioMeds', $userID);
      $nanoMeds = \App\Items::fetchByName('NanoMeds', $userID);
      $status = '';
      if ($herbMeds->quantity == 0 && $herbMeds->quantity == $bioMeds->quantity
        && $bioMeds->quantity == $nanoMeds->quantity){
        return $status;
      }
      if ($labor->actionsUntilSkill < 30){
        return "";
      }
      if ($nanoMeds->quantity > 0 && $user->useNanoMedsSetting){
        $nanoMeds->quantity--;
        $nanoMeds->save();
        $status = " You used your NanoMeds ";
        $medicated = true;
      }
      if (!$medicated && $bioMeds->quantity > 0 && $user->useBioMedsSetting){
        $bioMeds->quantity--;
        $bioMeds->save();
        $status = " You used your BioMeds ";
        if (rand(1, 5)){
          $medicated = true;
        }
      }
      if ($herbMeds->quantity > 0 && $user->useHerbMedsSetting){
        $herbMeds->quantity--;
        $herbMeds->save();
        $status = " You used your HerbMeds ";
        if (rand(1, 10)){
          $medicated = true;
        }
      }
      $medicatedStatus = " but it didn't do anything";
      if ($medicated){
        $labor->actionsUntilSkill *= .99;
        $labor->save();
        $medicatedStatus = " and now you learn a lil bit faster.";
      }
      return $status . $medicatedStatus;
    }

}
