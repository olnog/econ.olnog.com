<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Skills extends Model
{
  protected $table = 'skills';
  public static function decrease($id){
    $skill = \App\Skills::find($id);
    $labor = \App\Labor::fetch();
    if ($skill->rank < 1){
      return;
    }
    $skill->rank = 0;
    $skill->save();
    $labor->availableSkillPoints++;
    $labor->allocatedSkillPoints--;
    $labor->save();
    $skillType = \App\SkillTypes::find($skill->skillTypeID);
    \App\Skills::lock($skill->rank, $skillType->identifier);

  }

  public static function fetch(){
    return \App\Skills::where('userID', Auth::id())
      ->join('skillTypes', 'skills.skillTypeID', 'skillTypes.id')
      ->select('skills.id', 'skillTypeID', 'rank', 'name', 'description',
      'buildings', 'equipment', 'identifier')->where('skills.unlocked', true)
      ->orderBy('name')->get();
  }

  static public function fetchByIdentifier($identifier, $userID){

    $skillType = \App\SkillTypes::where('identifier', $identifier)->first();
    return \App\Skills::where('skillTypeID', $skillType->id)->where('userID', $userID)->first();
  }

  public static function fetchSkillUnlocks(){
    return [
      'mining'      => ['miningStone', 'miningIron', 'miningCoal', 'miningCopper',
        'miningSand', 'miningUranium'],
        //all mining tested
      'smelting'    => ['smeltingIron', 'smeltingSteel', 'smeltingCopper'],
      //smelt copper tested
      //smelt iron tested

      'toolmaking'  => ['toolmakingStone', 'toolmakingIron', 'toolmakingPowered', 'toolmakingSteel'],
      //all toolamking tested with axe
      'farming'     => ['farmingWheat', 'farmingRubber', 'farmingPlantX',
        'farmingHerbalGreens'],
      //tested harvest-rubber and all plant actions
      'engineering' => ['electricalEngineering', 'petroleumEngineering',
        'chemicalEngineering', 'biologicalEngineering', 'nuclearEngineering',
        'aerospaceEngineering', 'geneticEngineering', 'vehicularEngineering'],
        //make-cpu, make-solar-panel, pump-oil tested
    ];
  }

  public static function increase($identifier){
    $labor = \App\Labor::where('userID', Auth::id())->first();
    $skillType = SkillTypes::where('identifier', $identifier)->first();
    $skill = Skills::where('skillTypeID', $skillType->id)->where('userID', Auth::id())->first();
    if ($labor->availableSkillPoints < $skill->rank + 1){
      return;
    }

    \App\History::new(Auth::id(), 'skills', " You've allocated a skill point to " . $skillType->name . ".");
    $skill->rank++;
    $labor->allocatedSkillPoints += $skill->rank;
    $labor->availableSkillPoints -= $skill->rank;
    $labor->save();
    $skill->save();
    \App\Skills::unlock($skill->rank, $skillType->identifier);
  }

  public static function lock($rank, $identifier){
    $skillUnlocks = \App\Skills::fetchSkillUnlocks();
    if ($rank == 0 && array_key_exists($identifier, $skillUnlocks)){
      foreach($skillUnlocks[$identifier] as $skillUnlock){
        $newSkill = \App\Skills::fetchByIdentifier($skillUnlock, \Auth::id());
        $newSkill->unlocked = false;
        $newSkill->save();
      }
    }
  }

  public static function reset(){
    $skills = \App\Skills::where('userID', Auth::id())->get();
    foreach ($skills as $skill){
      $skillType = \App\SkillTypes::find($skill->skillTypeID);
      $skill->unlocked = $skillType->unlocked;
      $skill->rank = 0;
      $skill->save();
    }
  }

  public function skillType(){
    return $this->hasOne('App\SkillTypes', 'skillTypeID');
  }

  public static function unlock($rank, $identifier){
    $skillUnlocks = \App\Skills::fetchSkillUnlocks();
    if ($rank == 1 && array_key_exists($identifier, $skillUnlocks)){
      foreach($skillUnlocks[$identifier] as $skillUnlock){
        $newSkill = \App\Skills::fetchByIdentifier($skillUnlock, \Auth::id());
        $newSkill->unlocked = true;
        $newSkill->save();
      }
    }
  }
}
