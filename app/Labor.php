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
      $couldTheySwitch = \App\Labor::couldTheySwitch($itemName, $userID);
      if ($couldTheySwitch){
        return true;
      }
      $labor = Labor::where('userID', $userID)->first();
      if ($labor->equipped == null){
        return false;
      }
      $equipment = Equipment::find($labor->equipped);
      if (substr($equipment->type->name, 0, strlen($itemName)) == $itemName){
        return true;
      }
      return false;
    }

    public static function couldTheySwitch($itemName, $userID){
        $allEquipment = \App\Equipment::fetch();
        foreach ($allEquipment as $equipment){
          if (substr($equipment->name, 0, strlen($itemName)) == $itemName){
            return true;
          }
        }
        return false;
    }

    public static function doAction($userID, $actionID){
      $action = \App\Actions::find($actionID);
      $labor = \App\Labor::where('userID', $userID)->first();
      $labor->actions++;
      if ($labor->actions >= $labor->actionsUntilSkill){
        $labor->actionsUntilSkill *= 2;
        $labor->actions=0;
        \App\Labor::incrementSkillPoints($userID);
      }
      $labor->save();
      $action->totalUses++;
      if ($action->totalUses >= $action->nextRank){
        $action->rank++;
        $action->nextRank *= 4;
      }
      $action->save();
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
      return .5;
    }

    public static function incrementSkillPoints($userID){
      $labor = \App\Labor::where('userID', $userID)->first();
      $labor->availableSkillPoints++;
      $labor->save();
    }

    public static function rebirth($legacy, $immortality){
      $newAvailableSkillPoints = 4;
      $labor = \App\Labor::fetch();
      if (!$labor->rebirth){
        return;
      }
      $tax = \App\Labor::fetchTax();
      $user = \App\User::find(\Auth::id());
      $estateTax = ceil($tax * $user->clacks);
      $user->clacks -=  $estateTax;
      if ($user->clacks < 0){
        $user->clacks = 0;
      }
      $user->save();
      if ($legacy){
        $children = \App\Items::fetchByName('Children', \Auth::id());
        if ($children->quantity < 1){
          return;
        }
        $children->quantity--;
        $children->save();
      }
      if ($immortality){
        $clones = \App\Items::fetchByName('Clones', \Auth::id());
        if ($clones->quantity < 1){
          return;
        }
        $clones->quantity--;
        $clones->save();
        $newAvailableSkillPoints = \App\Actions::where('userID', \Auth::id())
          ->where('unlocked', true)->count();
      }
      if (!$immortality){
        $labor->actions = 0;
        $labor->actionsUntilSkill = 30;
      }
      \App\Actions::reset($legacy ||  $immortality);
      $labor->availableSkillPoints = $newAvailableSkillPoints;
      $labor->allocatedSkillPoints = 0;
      $labor->rebirth = false;
      $labor->save();
      $corpse = \App\Items::fetchByName('Corpse', Auth::id());
      $corpse->quantity++;
      $corpse->save();
      \App\History::new(Auth::id(), 'rebirth',
        "Rebirth! Corpse: <span class='fp'>+1</span> Clacks: <span class='fn'>-"
          . number_format($estateTax) . "</span> [" . number_format($user->clacks)
          . "]");
      if ($labor->escrow > 0 ){
        $user->clacks += $labor->escrow;
        $user->save();
        \App\History::new(Auth::id(), 'rebirth',
          "Reproduction Contract! Clacks: <span class='fp'>+"
          . number_format($labor->escrow) . "</span> ["
          . number_format($user->clacks) . "]");
        $labor->escrow = 0;
        $labor->save();
      }
      $contracts = \App\Contracts::where('category', 'freelance')
        ->where('userID', $user->id)->where('active', 1)->get();
      foreach($contracts as $contract){
        $contract->active = 0;
        $contract->save();
      }
      if (count($contracts) > 0){
        \App\History::new(Auth::id(), 'rebirth',
          "Because of your rebirth, " . count($contracts)
          . " of your freelance contracts were canceled.");
      }
    }

    public static function switchEquipped($itemName, $userID){
      $equipment = \App\Equipment::fetchByName($itemName, $userID);
      $labor = \App\Labor::where('userID', $userID)->first();
      if ($equipment == null){
        return false;
      }
      $labor->equipped = $equipment->id;
      $labor->save();
      return true;
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
