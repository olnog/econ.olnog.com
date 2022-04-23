<?php
namespace App;


use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use \App\Skills;
use \App\Labor;

class Actions extends Model
{
  protected $table = 'actions';

  public static function fetch($userID){
      return [
        'unlocked'=>\App\Actions::fetchUnlocked($userID),
        'possible'=>\App\Actions::available($userID),
      ];
  }

  public static function fetchUnlocked($userID){
    return \App\Actions
      ::join('action_types', 'actions.actionTypeID', 'action_types.id')
      ->where('userID', $userID)->where('unlocked', true)
      ->select('name', 'actions.id', 'actionTypeID', 'totalUses', 'nextRank',
      'rank', 'unlocked')->get();
  }

  public static function fetchByName($userID, $name){
    $actionType = \App\ActionTypes::where('name', $name)->first();
    return \App\Actions::where('actionTypeID', $actionType->id)
      ->where('userID', $userID)->first();
  }

    public static function available($userID){
      $availableActions = [];
      $availableBuildings = null;
      $labor = \App\Labor::where('userID', \Auth::id())->first();
      //if (Skills::fetchByIdentifier('construction', Auth::id())->rank > 0){
        //$availableBuildings  = Actions::fetchAvailableBuildings();
      //}
      $solarElectricity = 0;
      if (\App\Buildings::doesItExist('Solar Power Plant', Auth::id())){
        $solarPowerPlant = \App\Buildings::fetchByName('Solar Power Plant', \Auth::id() );
        $solarElectricity = $solarPowerPlant->electricity;
      }


      $possibleActions = \App\Actions::fetchUnlocked($userID);
      foreach ($possibleActions as $action){
        $actionName = $action->name;
        if ($actionName == 'chop-tree'
        && ((!Labor::areTheyEquippedWith('Axe', Auth::id())
        && !Labor::areTheyEquippedWith('Chainsaw (electric)', Auth::id())
        && !Labor::areTheyEquippedWith('Chainsaw (gas)', Auth::id()))
          || !Land::doTheyHaveAccessTo('forest'))){
          continue;
        } else if ($actionName == 'cook-meat'
          && (
            !\App\Items::doTheyHave('Meat', 1)
            || !\App\Buildings::didTheyAlreadyBuildThis('Campfire', Auth::id())
            && !\App\Buildings::didTheyAlreadyBuildThis('Kitchen', Auth::id())
            && !\App\Buildings::didTheyAlreadyBuildThis('Food Factory', Auth::id())
            )){
          continue;
        } else if ($actionName == 'cook-flour'
          && (
            !\App\Items::doTheyHave('Flour', 1)
            || !\App\Buildings::didTheyAlreadyBuildThis('Campfire', Auth::id())
            && !\App\Buildings::didTheyAlreadyBuildThis('Kitchen', Auth::id())
            && !\App\Buildings::didTheyAlreadyBuildThis('Food Factory', Auth::id())
          )){
          continue;

      } else if ($actionName == 'convert-corpse-to-genetic-material'
        && (!\App\Buildings::doesItExist('Clone Vat', Auth::id())
        || !\App\Items::doTheyHave('Corpse', 1)
        || !\App\Items::doTheyHave('Electricity', 1000)
        )){
        continue;
        } else if ($actionName == 'convert-wheat-to-Bio-Material'
          && (!\App\Buildings::doesItExist('Bio Lab', Auth::id())
          || !\App\Items::doTheyHave('Wheat', 100)
          || !\App\Items::doTheyHave('Electricity', 100)
          )){
          continue;
        } else if ($actionName == 'convert-corpse-to-Bio-Material'
          && (!\App\Buildings::doesItExist('Bio Lab', Auth::id())
          || !\App\Items::doTheyHave('Corpse', 1)
          || !\App\Items::doTheyHave('Electricity', 100)
          )){
          continue;
        } else if ($actionName == 'convert-herbal-greens-to-Bio-Material'
          && (!\App\Buildings::doesItExist('Bio Lab', Auth::id())
          || !\App\Items::doTheyHave('Herbal Greens', 100)
          || !\App\Items::doTheyHave('Electricity', 100)
          )){
          continue;
        } else if ($actionName == 'convert-plant-x-to-Bio-Material'
          && (!\App\Buildings::doesItExist('Bio Lab', Auth::id())
          || !\App\Items::doTheyHave('Plant X', 100)
          || !\App\Items::doTheyHave('Electricity', 100)
          )){
          continue;
        } else if ($actionName == 'convert-meat-to-Bio-Material'
          && (!\App\Buildings::doesItExist('Bio Lab', Auth::id())
          || !\App\Items::doTheyHave('Meat', 100)
          || !\App\Items::doTheyHave('Electricity', 100)
          )){
          continue;
        } else if ($actionName == 'convert-sand-to-silicon'
          && (!\App\Buildings::doesItExist('Chem Lab', Auth::id())
          || !\App\Items::doTheyHave('Sand', 1000)
          || !\App\Items::doTheyHave('Electricity', 100)
        )){
          continue;
        } else if ($actionName == 'convert-coal-to-carbon-nanotubes'
          && (!\App\Buildings::doesItExist('Chem Lab', Auth::id())
          || !\App\Items::doTheyHave('Coal', 1000)
          || !\App\Items::doTheyHave('Electricity', 100)
        )){
          continue;
        } else if ($actionName == 'convert-wood-to-carbon-nanotubes'
          && (!\App\Buildings::doesItExist('Chem Lab', Auth::id())
          || !\App\Items::doTheyHave('Wood', 1000)
          || !\App\Items::doTheyHave('Electricity', 100)
        )){
          continue;
        } else if ($actionName == 'convert-wood-to-coal'
          && (!\App\Buildings::doesItExist('Chem Lab', Auth::id())
          || !\App\Items::doTheyHave('Wood', 1000)
          || !\App\Items::doTheyHave('Electricity', 100)
        )){
          continue;
        } else if ($actionName == 'convert-uranium-ore-to-plutonium'
          && (!\App\Buildings::doesItExist('Centrifuge', Auth::id())
          || !\App\Items::doTheyHave('Uranium Ore', 1000)
          || !\App\Items::doTheyHave('Electricity', 1000)
        )){
          continue;
        } else if ($actionName == 'generate-electricity-with-coal'
          && (!\App\Buildings::doesItExist('Coal Power Plant', Auth::id())
          || !\App\Items::doTheyHave('Coal', 1000)
        )){
          continue;
        } else if ($actionName == 'generate-electricity-with-plutonium'
          && (!\App\Buildings::doesItExist('Nuclear Power Plant', Auth::id())
          || !\App\Items::doTheyHave('Plutonium', 1000)
        )){
          continue;
        } else if ($actionName == 'harvest-herbal-greens'
          && (!\App\Buildings::doesItExist('Herbal Greens Field', Auth::id())
          || !\App\Buildings::canTheyHarvest('Herbal Greens Field', Auth::id()))){
          continue;
        } else if ($actionName == 'harvest-plant-x'
          && (!\App\Buildings::doesItExist('Plant X Field', Auth::id())
          || !\App\Buildings::canTheyHarvest('Plant X Field', Auth::id()))){
          continue;
        } else if ($actionName == 'harvest-wheat'
          && (!\App\Buildings::doesItExist('Wheat Field', Auth::id())
          || !\App\Buildings::canTheyHarvest('Wheat Field', Auth::id()))){
          continue;
        } else if ($actionName == 'harvest-rubber'
          && (!\App\Buildings::doesItExist('Rubber Plantation', Auth::id())
          || !\App\Buildings::canTheyHarvest('Rubber Plantation', Auth::id()))){

          continue;
      } else if ($actionName == 'make-BioMeds'
        && (!\App\Buildings::doesItExist('Bio Lab', Auth::id())
        || !\App\Items::doTheyHave('Electricity', 10)
        || !\App\Items::doTheyHave('HerbMeds', 10)
        || !\App\Items::doTheyHave('Bio Material', 10)
        )){
          continue;
        } else if ($actionName == 'make-book'
          &&  (!\App\Items::doTheyHave('Paper', 100) || $labor->availableSkillPoints < 1)
        ){
          continue;

        } else if ($actionName == 'make-contract'
          && (!\App\Items::doTheyHave('Paper', 1))){
          continue;

        } else if ($actionName == 'make-CPU'
          && (!\App\Buildings::doesItExist('CPU Fabrication Plant', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 1000)
          || !\App\Items::doTheyHave('Silicon', 100)
          || !\App\Items::doTheyHave('Copper Ingots', 100)
          )){
          continue;

        } else if ($actionName == 'make-diesel-bulldozer'
          && (!\App\Buildings::doesItExist('Garage', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 1000)
          || !\App\Items::doTheyHave('Steel Ingots', 250)
          || !\App\Items::doTheyHave('Copper Ingots', 50)
          || !\App\Items::doTheyHave('Diesel Engines', 1)
          )){
          continue;
        } else if ($actionName == 'make-gasoline-bulldozer'
          && (!\App\Buildings::doesItExist('Garage', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 1000)
          || !\App\Items::doTheyHave('Steel Ingots', 250)
          || !\App\Items::doTheyHave('Copper Ingots', 50)
          || !\App\Items::doTheyHave('Gasoline Engines', 1)
          )){
          continue;
        } else if ($actionName == 'make-gasoline-car'
          && (!\App\Buildings::doesItExist('Garage', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 1000)
          || !\App\Items::doTheyHave('Steel Ingots', 50)
          || !\App\Items::doTheyHave('Copper Ingots', 10)
          || !\App\Items::doTheyHave('Tires', 4)
          || !\App\Items::doTheyHave('Gasoline Engines', 1)
          )){
          continue;
        } else if ($actionName == 'make-diesel-car'
          && (!\App\Buildings::doesItExist('Garage', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 1000)
          || !\App\Items::doTheyHave('Steel Ingots', 50)
          || !\App\Items::doTheyHave('Copper Ingots', 10)
          || !\App\Items::doTheyHave('Tires', 4)
          || !\App\Items::doTheyHave('Diesel Engines', 1)
          )){
          continue;
        } else if ($actionName == 'make-diesel-tractor'
          && (!\App\Buildings::doesItExist('Garage', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 1000)
          || !\App\Items::doTheyHave('Steel Ingots', 100)
          || !\App\Items::doTheyHave('Copper Ingots', 20)
          || !\App\Items::doTheyHave('Tires', 4)
          || !\App\Items::doTheyHave('Diesel Engines', 1)
          )){
          continue;
        } else if ($actionName == 'make-gasoline-tractor'
          && (!\App\Buildings::doesItExist('Garage', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 1000)
          || !\App\Items::doTheyHave('Steel Ingots', 100)
          || !\App\Items::doTheyHave('Copper Ingots', 20)
          || !\App\Items::doTheyHave('Tires', 4)
          || !\App\Items::doTheyHave('Gasoline Engines', 1)
          )){
          continue;
        } else if (($actionName == 'make-electric-jackhammer' || $actionName == 'make-electric-chainsaw')
          && (!\App\Items::doTheyHave('Electric Motors', 1)
          || !\App\Items::doTheyHave('Steel Ingots', 10))){
            continue;
        } else if (($actionName == 'make-gas-jackhammer' || $actionName == 'make-gas-chainsaw')
          && (!\App\Items::doTheyHave('Gas Motors', 1)
          || !\App\Items::doTheyHave('Steel Ingots', 10))){
            continue;
        } else if (($actionName == 'make-diesel-engine' || $actionName == 'make-gasoline-engine')
          && (!\App\Buildings::doesItExist('Machine Shop', Auth::id())
          || !\App\Items::doTheyHave('Iron Ingots', 40)
          || !\App\Items::doTheyHave('Steel Ingots', 40)
          || !\App\Items::doTheyHave('Copper Ingots', 20)
          )){
            continue;
        } else if (($actionName == 'make-electric-motor' || $actionName == 'make-gas-motor')
          && (!\App\Buildings::doesItExist('Machine Shop', Auth::id())
          || !\App\Items::doTheyHave('Iron Ingots', 10)
          || !\App\Items::doTheyHave('Steel Ingots', 10)
          || !\App\Items::doTheyHave('Copper Ingots', 50)
          )){
            continue;

        } else if ($actionName == 'make-HerbMed'
          && (!\App\Items::doTheyHave('Herbal Greens', 10))){
          continue;
        } else if (($actionName == 'make-iron-axe'
          || $actionName == 'make-iron-pickaxe'
          || $actionName == 'make-iron-saw'
          || $actionName == 'make-iron-shovel'
          || $actionName == "make-iron-handmill")
          && (!\App\Items::doTheyHave('Iron Ingots', 1)
          || !\App\Items::doTheyHave('Wood', 1))){

          continue;
        } else if ($actionName == 'make-nanites'
          && (!\App\Buildings::doesItExist('Nano Lab', Auth::id())
          || !\App\Items::doTheyHave('Carbon Nanotubes', 100)
          || !\App\Items::doTheyHave('Silicon', 100)
          || !\App\Items::doTheyHave('Electricity', 1000)
          )){
          continue;
        } else if ($actionName == 'make-NanoMeds'
          && (!\App\Buildings::doesItExist('Nano Lab', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 100)
          || !\App\Items::doTheyHave('BioMeds', 10)
          || !\App\Items::doTheyHave('Nanites', 10)
          )){
            continue;

        } else if ($actionName == 'make-paper'
          && (!\App\Items::doTheyHave('Wood', 1))){
          continue;

        } else if ($actionName == 'make-rocket-engine'
        && (!\App\Buildings::doesItExist('Propulsion Lab', Auth::id())
        || !\App\Items::doTheyHave('Electricity', 1000)
        || !\App\Items::doTheyHave('Jet Fuel', 1000)
        || !\App\Items::doTheyHave('Iron Ingots', 1000)
        || !\App\Items::doTheyHave('Steel Ingots', 1000)
        )){
        continue;

        } else if ($actionName == 'make-solar-panel'
          && (!\App\Buildings::doesItExist('Solar Panel Fabrication Plant', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 100)
          || !\App\Items::doTheyHave('Silicon', 100)
          || !\App\Items::doTheyHave('Copper Ingots', 100)
          || !\App\Items::doTheyHave('Steel Ingots', 100)
          )){
          continue;
        } else if (($actionName == 'make-steel-axe'
          || $actionName == 'make-steel-pickaxe'
          || $actionName == 'make-steel-saw'
          || $actionName == 'make-steel-shovel'
          || $actionName == "make-steel-handmill")
          && (!\App\Items::doTheyHave('Steel Ingots', 1)
          || !\App\Items::doTheyHave('Wood', 1))){
          continue;
        } else if (($actionName == 'make-stone-axe'
          || $actionName == 'make-stone-pickaxe'
          || $actionName == 'make-stone-saw'
          || $actionName == 'make-stone-shovel'
          || $actionName == "make-stone-handmill")
          && (!\App\Items::doTheyHave('Stone', 1)
          || !\App\Items::doTheyHave('Wood', 1))){
          continue;

        } else if ($actionName == 'make-tire'
          && (!\App\Buildings::doesItExist('Chem Lab', Auth::id())
          || !\App\Items::doTheyHave('Rubber', 10)
          || !\App\Items::doTheyHave('Electricity', 10)
        )){
          continue;
        } else if ($actionName == 'make-radiation-suit'
          && (!\App\Buildings::doesItExist('Chem Lab', Auth::id())
          || !\App\Items::doTheyHave('Rubber', 100)
          || !\App\Items::doTheyHave('Electricity', 100)
        )){
          continue;
        } else if ($actionName == 'make-robot'
          && (!\App\Buildings::doesItExist('Robotics Lab', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 100000)
          || !\App\Items::doTheyHave('Steel Ingots', 100)
          || !\App\Items::doTheyHave('Copper Ingots', 100)
          || !\App\Items::doTheyHave('CPU', 10)
          || !\App\Items::doTheyHave('Electric Motors', 100)
        )){
          continue;

        } else if ($actionName == 'make-satellite'
          && (!\App\Buildings::doesItExist('Propulsion Lab', Auth::id())
          || !\App\Items::doTheyHave('Rocket Engines', 1)
          || !\App\Items::doTheyHave('Electricity', 100)
          || !\App\Items::doTheyHave('Steel Ingots', 100)
          || !\App\Items::doTheyHave('Copper Ingots', 100)
          || !\App\Items::doTheyHave('CPU', 1)
          || !\App\Items::doTheyHave('Solar Panels', 5)
        )){
          continue;
        } else if ($actionName == 'mill-flour'
          && ((!Labor::areTheyEquippedWith('Handmill', Auth::id())
          && !\App\Buildings::didTheyAlreadyBuildThis('Gristmill', Auth::id()))
          || !Items::doTheyHave('Wheat', 1))){
          continue;

        } else if ($actionName == 'mill-log'
          && (!Labor::areTheyEquippedWith('Saw', Auth::id())
            || !Items::doTheyHave('Logs', 1))
            && (!\App\Buildings::didTheyAlreadyBuildThis('Sawmill', Auth::id())
            || !Items::doTheyHave('Logs', 10))){
          continue;
        } else if (($actionName == 'mine-sand')
          && ((!Labor::areTheyEquippedWith('Shovel', Auth::id())
          && !Labor::areTheyEquippedWith('Bulldozer (gasoline)', Auth::id())
          && !Labor::areTheyEquippedWith('Bulldozer (diesel)', Auth::id()))
          || !Land::doTheyHaveAccessTo('desert'))){
          continue;

        } else if (($actionName == 'mine-coal' || $actionName == 'mine-stone'
          || $actionName == 'mine-iron-ore' || $actionName == 'mine-copper-ore'
          || $actionName == 'mine-uranium-ore')
          && ((!Labor::areTheyEquippedWith('Pickaxe', Auth::id())
            && !Labor::areTheyEquippedWith('Jackhammer (electric)', Auth::id())
            && !Labor::areTheyEquippedWith('Jackhammer (gas)', Auth::id()))
            || !Land::doTheyHaveAccessTo('mountains'))){
          continue;
        } else if ($actionName == 'plant-rubber-plantation'
          && (\App\User::find(Auth::id())->buildingSlots<1
          || !Land::doTheyHaveAccessTo('jungle'))){
          continue;
        } else if (($actionName == 'plant-wheat-field'
        || $actionName == 'plant-herbal-greens-field'
        || $actionName == 'plant-plant-x-field')

          && (\App\User::find(Auth::id())->buildingSlots<1)){
          continue;
        } else if ($actionName == 'pump-oil'
          && (!\App\Buildings::didTheyAlreadyBuildThis('Oil Well', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 10))){
          continue;
        } else if ($actionName == 'refine-oil'
          && (!\App\Buildings::didTheyAlreadyBuildThis('Oil Refinery', Auth::id())
          || !\App\Items::doTheyHave('Electricity', 100)
          || !\App\Items::doTheyHave('Oil', 100)
        )){
          continue;
        } else if ($actionName == 'smelt-copper'
          && (!\App\Items::doTheyHave('Copper Ore', 10)
            || (!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', Auth::id())
          && !\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', Auth::id())
          && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', Auth::id())))
        ) {
          continue;
        } else if ($actionName == 'smelt-iron'
          && (!\App\Items::doTheyHave('Iron Ore', 10)
            || (!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', Auth::id())
          && !\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', Auth::id())
          && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', Auth::id())))
        ) {
          continue;
        } else if ($actionName == 'smelt-steel'
          && (!\App\Items::doTheyHave('Iron Ingots', 10)
            || (!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', Auth::id())
          && !\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', Auth::id())
          && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', Auth::id())))
        ) {
          continue;
        } else if ($actionName == 'transfer-electricity-from-solar-power-plant'
          && (!\App\Buildings::didTheyAlreadyBuildThis('Solar Power Plant', Auth::id())
          || $solarElectricity < 1
        )){
              continue;

        }
        $availableActions [] = $actionName;

      }
      return $availableActions;
      /*
      return ['possible' => $possibleActions, 'available'=>$availableActions,
        'buildings' => $availableBuildings, 'robots' => \App\Actions::fetchRobotActions()];
        */
    }



    public static function do($actionName, $consumption, $agentID, $contractorID, $robotID){
      \App\Metric::newAction($agentID, $actionName);
      $action = \App\Actions::fetchByName($agentID, $actionName);
      \App\Labor::doAction($agentID, $action->id);
      $status = "";
      $contractorCaption = " They ";
      $agentCaption = " They ";
      $radiationPoisoning = false;
      $radStatus = '';
      if ($contractorID == Auth::id()){
        $contractorCaption = " You ";
      }
      if ($agentID == Auth::id()){
        $agentCaption = " You ";
      }
      $robot = null;
      if ($robotID != null){
        $robot = \App\Robot::find($robotID);
        $agentCaption = "Robot #" . $robot->num . " ";
      }
      if ($robot == null && $agentID == $contractorID && strtotime('now') - strtotime(\App\User::find($agentID)->lastAction) == 0){
        return [
          'error' => "Sorry, you're doing this too often.",
        ];
      }
      if ($actionName == 'chop-tree'){
        $leaseStatus = '';
        $landBonus = \App\Land::count('forest', $agentID);
        $electricity = Items::fetchByName('Electricity', $contractorID);
        $gas = Items::fetchByName('Gasoline', $contractorID);
        if (($robot == null
          && !Labor::areTheyEquippedWith('Axe', $agentID)
          && !Labor::areTheyEquippedWith('Chainsaw (electric)', $agentID)
          && !Labor::areTheyEquippedWith('Chainsaw (gas)', $agentID))
          ||  ($robot != null
          && !\App\Robot::areTheyEquippedWith('Axe', $robotID)
          && !Labor::areTheyEquippedWith('Chainsaw (electric)', $agentID)
          && !Labor::areTheyEquippedWith('Chainsaw (gas)', $agentID))
          ){
          return [
            'error' => $agentCaption . " do not have anything equipped to chop down a tree."
          ];
        } else if (Labor::areTheyEquippedWith('Chainsaw (electric)', $agentID) && $electricity->quantity < 100){
          return [
            'error' => $agentCaption . " has an electric Chainsaw equipped but does not have enough Electricity to use it."
          ];
        } else if (Labor::areTheyEquippedWith('Chainsaw (gas)', $agentID) && $gas->quantity < 100){
          return [
            'error' => $agentCaption . " has a gas-powered Chainsaw equipped but does not have enough Gasoline to use it."
          ];
        }
        if (!\App\Land::doTheyOwn('forest', $agentID)){
          $currentlyLeasing = \App\Lease::areTheyAlreadyLeasing('forest', $agentID);
          if ($currentlyLeasing){

            $landBonus = 1;
            $leaseStatus = \App\Lease::use('forest', $agentID);
          }
          if ($leaseStatus == false || !$currentlyLeasing){
            return [
              'error' => $agentCaption . " don't have access to any Forests. Sorry."
            ];
          }
        }
        $baseChop = 10;
        if (($robot == null && \App\Labor::areTheyEquippedWith('Axe', $agentID))
          || ($robot != null && \App\Robot::areTheyEquippedWith('Axe', $robotID))){
          $baseChop = 1;
        }
        if ($robot == null){
          $equipmentCaption = Equipment::useEquipped($agentID);
          $logsChopped = $action->rank * $baseChop * $landBonus;
        } else {
          $equipmentCaption = \App\Robot::useEquipped($robotID);
          $logsChopped = $baseChop;
        }
        $fuelStatus = '';
        $landResource = \App\Land::takeResource('Logs',  $agentID, $logsChopped, true);
        if ($landResource != true){
          return $landResource;
        }
        if (($robot == null && Labor::areTheyEquippedWith('Chainsaw (electric)', $agentID))
          || ($robot != null && Robot::areTheyEquippedWith('Chainsaw (electric)', $agentID))
      ){
          $electricity->quantity -= 100;
          $electricity->save();
          $fuelStatus = "You used 100 Electricity. [" . number_format($electricity->quantity) . "]";
        } else if (($robot == null && Labor::areTheyEquippedWith('Chainsaw (gas)', $agentID))
            || ($robot != null && Robot::areTheyEquippedWith('Chainsaw (gas)', $agentID))
        ){
          $gas->quantity -= 100;
          $gas->save();
          $fuelStatus = "You used 100 Gasoline. [" . number_format($gas->quantity) . "]";
        }

        $logs = Items::fetchByName('Logs', $contractorID);
        $logs->quantity += $logsChopped;
        $logs->save();
        $status = $agentCaption . " have chopped down " . $logsChopped
          . " trees. "  . $fuelStatus . $equipmentCaption . $leaseStatus;
          if ($agentID == $contractorID){
            $status .= " You now have " . number_format($logs->quantity) . " logs. ";
          }




      } else if ($actionName == 'convert-corpse-to-genetic-material'){
        $corpse = \App\Items::fetchByName('Corpse', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Clone Vat', $agentID)){
          return [
            'error' => "You need to have a Clone Vat to do this.",
          ];
        } else if ($corpse->quantity < 1 ){
          return [
            'error' => "You don't have enough Corpse (1) to do this.",
          ];
        } else if ($electricity->quantity < 1000 ){
          return [
            'error' => "You don't have enough Electricity  (1000) to do this.",
          ];
        }
        $production = 100;
        if ($robot == null){
          $production = $action->rank * 100;
        }
        $buildingCaption = \App\Buildings::use('Clone Vat', $agentID);
        $corpse->quantity -= 1;
        $corpse->save();
        $electricity->quantity -= 1000;
        $electricity->save();
        $geneticMaterial = \App\Items::fetchByName('Genetic Material', $contractorID);
        $geneticMaterial->quantity += $production;
        $geneticMaterial->save();
        $status = $agentCaption . " used 1 Corpse [" . number_format($corpse->quantity)
        . "] and 1000 Electricity [" . number_format($electricity->quantity). "] to create "
          . ($production) . " Genetic Material. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($geneticMaterial->quantity) . ".";
        }



      } else if ($actionName == 'convert-wheat-to-Bio-Material'
        || $actionName == 'convert-corpse-to-Bio-Material'
        || $actionName == 'convert-herbal-greens-to-Bio-Material'
        || $actionName == 'convert-plant-x-to-Bio-Material'
        || $actionName == 'convert-meat-to-Bio-Material'){
        $itemNames = [
          'convert-wheat-to-Bio-Material' => 'Wheat',
          'convert-corpse-to-Bio-Material' => 'Corpse',
          'convert-herbal-greens-to-Bio-Material' => 'Herbal Greens',
          'convert-plant-x-to-Bio-Material' => 'Plant X',
          'convert-meat-to-Bio-Material' => 'Meat',
        ];
        $itemName = $itemNames[$actionName];
        $inputItem = \App\Items::fetchByName($itemName, $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $req = 100;
        if ($itemName == 'Corpse'){
          $req = 1;
        }
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Bio Lab', $agentID)){
          return [
            'error' => "You need to have a Bio Lab to do this.",
          ];
        } else if ($inputItem->quantity < $req ){
          return [
            'error' => "You don't have enough " . $itemName . " (" . $req . " needed) to do this.",
          ];
        } else if ($electricity->quantity < 100 ){
          return [
            'error' => "You don't have enough Electricity  (100) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Bio Lab', $agentID);
        $inputItem->quantity -= $req;
        $inputItem->save();
        $electricity->quantity -= 100;
        $electricity->save();
        $bioMaterial = \App\Items::fetchByName('Bio Material', $contractorID);
        $bioMaterial->quantity += $production;
        $bioMaterial->save();
        $status = $agentCaption .  " used " . $req . " " . $itemName . " [" . number_format($inputItem->quantity)
        . "] and 100 Electricity [" . number_format($electricity->quantity) . "] to create "
          . $production . " Bio Material. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($inputItem->quantity) . ".";
        }




      } else if ($actionName == 'convert-sand-to-silicon'){
        $sand = \App\Items::fetchByName('Sand', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $buildingCaption = "";
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $agentID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        } else if ($sand->quantity < 1000 ){
          return [
            'error' => "You don't have enough Sand (1000) to do this.",
          ];
        } else if ($electricity->quantity < 100 ){
          return [
            'error' => "You don't have enough Electricity  (100) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $agentID);
        $sand->quantity -= 1000;
        $sand->save();
        $electricity->quantity -= 100;
        $electricity->save();
        $silicon = \App\Items::fetchByName('Silicon', $contractorID);
        $silicon->quantity += $production;
        $silicon->save();
        $status = $agentCaption .  " used 1,000 Sand [" . number_format($sand->quantity)
        . "] and 100 Electricity [" . number_format($electricity->quantity) . "] to create "
          . $production . " Silicon. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($silicon->quantity) . ".";
        }



      } else if ($actionName == 'convert-wood-to-coal'){
        $production = 100;
        if ($robot == null){
          $production = $action->rank * 100;
        }
        $wood = \App\Items::fetchByName('Wood', $agentID);
        $coal = \App\Items::fetchByName('Coal', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $agentID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        } else if ($wood->quantity < 1000 ){
          return [
            'error' => "You don't have enough Wood (1000) to do this.",
          ];
        } else if ($electricity->quantity < 100 ){
          return [
            'error' => "You don't have enough Electricity  (100) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $agentID);
        $wood->quantity -= 1000;
        $wood->save();
        $electricity->quantity -= 100;
        $electricity->save();
        $coal->quantity += $production;
        $coal->save();
        $status = $agentCaption .  " used 1,000 Wood "
          . " [" . number_format($wood->quantity) . "] and 100 Electricity ["
          . number_format($electricity->quantity) . "] to create "
          . $production . " Coal. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($coal->quantity) . ".";
        }




      } else if ($actionName == 'convert-coal-to-carbon-nanotubes'
        || $actionName == 'convert-wood-to-carbon-nanotubes'){
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        $possibleInputs = [
          'convert-coal-to-carbon-nanotubes' => 'Coal',
          'convert-wood-to-carbon-nanotubes' => 'Wood'
        ];
        $itemInput = \App\Items::fetchByName($possibleInputs[$actionName], $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $agentID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        } else if ($itemInput->quantity < 1000 ){
          return [
            'error' => "You don't have enough Wood or Coal (1000) to do this.",
          ];
        } else if ($electricity->quantity < 100 ){
          return [
            'error' => "You don't have enough Electricity  (100) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $agentID);
        $itemInput->quantity -= 1000;
        $itemInput->save();
        $electricity->quantity -= 100;
        $electricity->save();
        $carbonNanotubes = \App\Items::fetchByName('Carbon Nanotubes', $contractorID);
        $carbonNanotubes->quantity += $production;
        $carbonNanotubes->save();
        $status = $agentCaption .  " used 1,000 " . $possibleInputs[$actionName]
          . " [" . number_format($itemInput->quantity) . "] and 100 Electricity ["
          . number_format($electricity->quantity) . "] to create "
          . $production . " Carbon Nanotubes. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($carbonNanotubes->quantity) . ".";
        }





      } else if ($actionName == 'convert-uranium-ore-to-plutonium'){
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        $uranium = \App\Items::fetchByName('Uranium Ore', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Centrifuge', $agentID)){
          return [
            'error' => "You need to have a Centrifuge to do this.",
          ];
        } else if ($uranium->quantity < 1000 ){
          return [
            'error' => "You don't have enough Uranium Ore (1000) to do this.",
          ];
        } else if ($electricity->quantity < 1000 ){
          return [
            'error' => "You don't have enough Electricity  (1000) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Centrifuge', $agentID);
        $uranium->quantity -= 1000;
        $uranium->save();
        $electricity->quantity -= 1000;
        $electricity->save();
        $plutonium = \App\Items::fetchByName('Plutonium', $contractorID);
        $plutonium->quantity += $production;
        $plutonium->save();
        $status = $agentCaption . " used 1,000 Uranium Ore [" . number_format($uranium->quantity)
        . "] and 1000 Electricity [" . number_format($electricity->quantity) . "] to create "
          . $production . " Plutonium. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($plutonium->quantity) . ".";
        }





      } else if ($actionName == 'cook-meat' || $actionName == 'cook-flour'){
        $foodSource = Items::fetchByName(ucfirst(explode('-', $actionName)[1]), $agentID);
        $food = Items::fetchByName('Food', $contractorID);
        $wood = Items::fetchByName('Wood', $agentID);
        $electricity = Items::fetchByName('Electricity', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Campfire', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Kitchen', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $agentID)
        ){
          return ['error' => "You don't have the necessary building."];
        } else if ($foodSource->quantity < 1
          || (\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $agentID)
          && $foodSource->quantity < 100)){
          return ['error' => "You don't have enough Meat or Flour."];
        } else if (\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $agentID) && $electricity->quantity < 100){
          return ['error' => "You don't have enough Electricity. (1000 needed)"];
        } else if (!\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $agentID) && $wood->quantity < 1){
          return ['error' => "You don't have enough Wood."];
        }
        $buildingName = 'Campfire';
        $modifier = 1;
        $woodUsed = 1;
        if (\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $agentID)
          && $electricity->quantity >= 100 && $foodSource->quantity >= 100){
          $buildingName='Food Factory';
          $modifier = 100;
        } else if ($foodSource->quantity >= 10 && $wood->quantity >= 5 && \App\Buildings::didTheyAlreadyBuildThis('Kitchen', $agentID)){
          $buildingName='Kitchen';
          $modifier = 10;
          $woodUsed = 5;
        } else if (!\App\Buildings::didTheyAlreadyBuildThis('Campfire', $agentID)){
          $buildingName='Kitchen';

        }
        $buildingCaption = \App\Buildings::use($buildingName, $agentID);
        $foodCooked = 2 * $modifier;
        if ($robot == null){
          $foodCooked = $action->rank * 2 * $modifier;
        }

        $foodSource->quantity -= $modifier;
        $foodSource->save();
        $food->quantity += $foodCooked;
        $food->save();
        if (!\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $agentID)){
          $wood->quantity -= $woodUsed;
          $wood->save();
          $status = "Using " . $woodUsed . " Wood, [" . number_format($wood->quantity) . "]"
            . $agentCaption . " cooked " .  $modifier .  " "
            . ucfirst(explode('-', $actionName)[1]) . " [" . number_format($foodSource->quantity) . "] into " . $foodCooked . " food. ".  $buildingCaption;
        } else {
          $electricity->quantity -= $woodUsed;
          $electricity->save();
          $status = "Using 100 Electricity, [" . number_format($electricity->quantity) . "] "
            . $agentCaption . " cooked " .  $modifier .  " "
            . ucfirst(explode('-', $actionName)[1]) . " [" . number_format($foodSource->quantity) . "] into " . $foodCooked . " Food. ".  $buildingCaption;
        }

        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($food->quantity) . ". ";
        }



      } else if ($actionName == 'explore'){
        $diesel = \App\Items::fetchByName('Diesel Fuel', $agentID);
        $gasoline = \App\Items::fetchByName('Gasoline', $agentID);
        $fuelStatus = '';
        $equipmentCaption = '';
        if (($robot == null
          && Labor::areTheyEquippedWith('Car (diesel)', $agentID)
          && $diesel->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Car (diesel)', $robotID)
          && $diesel->quantity < 100)
        ){
          return ['error' =>'You have a diesel Car equipped but do not have enough Diesel Fuel (100 needed).'];
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Car (gasoline)', $agentID)
          && $gasoline->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Car (gasoline)', $robotID)
          && $gasoline->quantity < 100)
        ){
          return ['error' =>'You have a gasoline Car equipped but do not have enough Gasoline (100 needed).'];
        }
        $land = \App\Land::all();
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }

        $satellite = \App\Items::fetchByName('Satellite', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $satStatus = "";
        $minChance = 1;
        if ($satellite->quantity > 0 && $electricity->quantity >= 100){
          $minChance = 100;
          $electricity->quantity -= 100;
          $electricity->save();
          $satStatus = " Using 100 Electricity, you used your Satellite to increase your chances";
          if (rand(1, 1000) == 1){
            $satellite->quantity--;
            $satellite->save();
            $satStatus .= " but it randomly malfunctioned and no longer works now";
          }
          $satStatus .= ".";
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Car (gasoline)', $agentID)
          && $gasoline->quantity >= 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Car (gasoline)', $robotID)
          && $gasoline->quantity >= 100)
        ){
          $minChance = 10;
          $gasoline->quantity -= 100;
          $gasoline->save();
          $fuelStatus = " You used 100 Gasoline. [" . number_format($gasoline->quantity) . "] ";
          if ($robot == null){
            $equipmentCaption = \App\Equipment::useEquipped($agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Car (diesel)', $agentID)
          && $diesel->quantity >= 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Car (diesel)', $robotID)
          && $diesel->quantity >= 100)
        ){
          $minChance = 10;
          $diesel->quantity -= 100;
          $diesel->save();
          $fuelStatus = " You used 100 Diesel Fuel. [" . number_format($diesel->quantity) . "] ";
          if ($robot == null){
            $equipmentCaption = \App\Equipment::useEquipped($agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
        }


        $status .= $agentCaption . " explored but discovered no new land. (" . $minChance . " out of " . count($land) . " chance)";
        $landFound = " [ ";
        if (rand(1, count($land)+1) <= $minChance){
          for ($i=0; $i < $production; $i++){
            $landFound .=  \App\Land::new($contractorID) . " ";
          }
          $status = $agentCaption . " explored and discovered a new piece of land.";
          if ($production > 1){
            $status = $agentCaption . " explored and discovered " . $exploringSkill->rank . " new pieces of land.";
          }
        }
        $landFound .= "]";
        $status .= $landFound . $satStatus . $equipmentCaption . $fuelStatus;



      } else if ($actionName == 'gather-stone'){
        $stoneTypeID = ItemTypes::where('name', 'Stone')->first()->id;
        $stone = Items::where('userID', $contractorID)->where('itemTypeID', $stoneTypeID)->first();
        $stone->quantity++;
        $stone->save();
        $status = $agentCaption . " gathered 1 stone. ";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($stone->quantity) . ".";
        }



      } else if ($actionName == 'gather-wood'){
        $woodTypeID = ItemTypes::where('name', 'Wood')->first()->id;
        $wood = Items::where('userID', $contractorID)->where('itemTypeID', $woodTypeID)->first();
        $wood->quantity += 10;
        $wood->save();
        $status = $agentCaption . " gathered 10 wood. " ;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($wood->quantity) . ".";
        }



      } else if ($actionName == 'generate-electricity-with-coal'){
        $buildingCaption = "";
        $coal = Items::fetchByName('Coal', $agentID);
        if (!\App\Buildings::doesItExist('Coal Power Plant', $agentID)){
          return ['error' => 'You do not have a Coal Power Plant. Build one first please. '];
        } else if ($coal->quantity < 1000){
          return ['error' => 'You do not have enough Coal. You need 1000. '];
        }
        $buildingCaption = \App\Buildings::use('Coal Power Plant', $agentID);
        $production = 1000;
        if ($robot == null){
          $production = $action->rank * 1000;
        }
        $electricity = Items::fetchByName('Electricity', $agentID);
        $coal->quantity -= 1000;
        $coal->save();
        $electricity->quantity += $production;
        $electricity->save();
        $status = $agentCaption . " used 1000 Coal [ "
          . number_format($coal->quantity) . " ] to generate "
          . number_format($production) . " Electricity. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($electricity->quantity) . ".";
        }



      } else if ($actionName == 'generate-electricity-with-plutonium'){
        if ($robot != null){
          return;
        }
        $buildingCaption = "";
        $plutonium = Items::fetchByName('Plutonium', $agentID);
        if (!\App\Buildings::doesItExist('Nuclear Power Plant', $agentID)){
          return ['error' => 'You do not have a Nuclear Power Plant. Build one first please. '];
        } else if ($plutonium->quantity < 1000){
          return ['error' => 'You do not have enough Plutonium. You need 1000. '];
        }
        $buildingCaption = \App\Buildings::use('Nuclear Power Plant', $agentID);
        $electricityProduced = $action->rank * 1000000;

        $electricity = Items::fetchByName('Electricity', $agentID);
        $plutonium->quantity -= 1000;
        $plutonium->save();
        $electricity->quantity += $electricityProduced;
        $electricity->save();
        $nuclearWaste = Items::fetchByName('Nuclear Waste', $agentID);
        $nuclearWaste->quantity += 1000;
        $nuclearWaste->save();
        $status = $agentCaption . " used 1,000 Plutonium [ " . number_format($plutonium->quantity)
          . " ] to generate " . number_format($electricityProduced)
          . " Electricity. This process created 1,000 Nuclear Waste. ["
          . number_format($nuclearWaste->quantity) . "] " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($electricity->quantity) . ".";
        }



      } else if ($actionName == 'harvest-rubber'){
        $diesel = Items::fetchByName('Diesel Fuel', $agentID);
        $equipmentCaption = "";
        $fuelStatus = '';
        $gasoline = Items::fetchByName('Gasoline', $agentID);
        $howManyFields = 1;
        $totalRubberYield = 0;
        $tractorUsed = false;
        if (!\App\Buildings::doesItExist('Rubber Plantation', $contractorID)
        || !\App\Buildings::canTheyHarvest('Rubber Plantation', $contractorID)){
          return ['error' => "You either do not have a Rubber Plantation or cannot harvest one right now. Sorry."];
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (diesel)', $agentID)
          && $diesel->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (diesel)', $robotID)
          && $diesel->quantity < 100)
        ){
          $fuelStatus = 'You have a diesel Tractor equipped but do not have enough Diesel Fuel (100 needed).';
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (gasoline)', $agentID)
          && $gasoline->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (gasoline)', $robotID)
          && $gasoline->quantity < 100)
        ){
          $fuelStatus = 'You have a gasoline Tractor equipped but do not have enough Gasoline (100 needed).';
        }

        if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (gasoline)', $agentID))
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (gasoline)', $robotID))
        ){
          $tractorUsed = true;
          $gasoline->quantity -= 100;
          $gasoline->save();
          $fuelStatus = " You used 100 Gasoline ["
            . number_format($gasoline->quantity) . "]";
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (diesel)', $agentID))
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (diesel)', $robotID))
        ){
          $tractorUsed = true;
          $diesel->quantity -= 100;
          $diesel->save();
          $fuelStatus = " You used 100 Diesel Fuel ["
            . number_format($diesel->quantity) . "]";
        }
        if ($tractorUsed){
          $howManyFields = \App\Buildings::howManyFields('Rubber Plantation', $contractorID);
          if ($howManyFields > 10){
            $howManyFields = 10;
          }
          if ($robot == null){
            $equipmentCaption = \App\Equipment::useEquipped($agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
        }
        for ($i = 0; $i < $howManyFields; $i++){
          $field = \App\Buildings::fetchField('Rubber Plantation', $contractorID);
          $rubberYield = $field->rubber;
          if ($robot == null){
            $rubberYield = $field->rubber * $action->rank;
          }
          $rubber = Items::fetchByName('Rubber', $contractorID);
          \App\Buildings::destroy($field->id);
          $user = \App\User::find($contractorID);
          $user->buildingSlots++;
          $user->save();
          $rubber->quantity += $rubberYield;
          $rubber->save();
          $totalRubberYield += $rubberYield;
        }
        $status = $agentCaption . " harvested " . $rubberYield . " Rubber from "
        . $howManyFields . " Rubber Plantation(s)." . $equipmentCaption . $fuelStatus;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($rubber->quantity) . ".";
        }



      } else if ($actionName == 'harvest-wheat'
        || $actionName == 'harvest-plant-x'
        || $actionName == 'harvest-herbal-greens'){
        $diesel = Items::fetchByName('Diesel Fuel', $agentID);
        $equipmentCaption = '';
        $fuelStatus = '';
        $gasoline = Items::fetchByName('Gasoline', $agentID);
        $howManyFields = 1;
        $totalYield = 0;
        $tractorUsed = false;
        $whichSkillName = [
          'harvest-wheat' => 'farmingWheat',
          'harvest-plant-x' => 'farmingPlantX',
          'harvest-herbal-greens'=> 'farmingHerbalGreens'
        ];
        $whichItemType = [
          'harvest-wheat' => 'Wheat',
          'harvest-plant-x' => 'Plant X',
          'harvest-herbal-greens'=> 'Herbal Greens'
        ];
        $whichVarName = [
          'harvest-wheat' => 'wheat',
          'harvest-plant-x' => 'plantX',
          'harvest-herbal-greens'=> 'herbalGreens'
        ];
        if (!\App\Buildings::doesItExist($whichItemType[$actionName] . ' Field', $contractorID)
        || !\App\Buildings::canTheyHarvest($whichItemType[$actionName] . ' Field', $contractorID)){
          return ['error' => "You either do not have a " . $whichItemType[$actionName] . " Field or cannot harvest one right now. Sorry."];
        }else if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (diesel)', $agentID)
          && $diesel->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (diesel)', $robotID)
          && $diesel->quantity < 100)
        ){
          return ['error' =>'You have a diesel Tractor equipped but do not have enough Diesel Fuel (100 needed).'];
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (gasoline)', $agentID)
          && $gasoline->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (gasoline)', $robotID)
          && $gasoline->quantity < 100)
        ){
          return ['error' =>'You have a gasoline Tractor equipped but do not have enough Gasoline (100 needed).'];
        }

        if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (gasoline)', $agentID))
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (gasoline)', $robotID))
        ){
          $tractorUsed = true;
          $gasoline->quantity -= 100;
          $gasoline->save();
          $fuelStatus = " You used 100 Gasoline ["
            . number_format($gasoline->quantity) . "]";
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Tractor (diesel)', $agentID))
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Tractor (diesel)', $robotID))
        ){
          $tractorUsed = true;
          $diesel->quantity -= 100;
          $diesel->save();
          $fuelStatus = " You used 100 Diesel Fuel ["
            . number_format($diesel->quantity) . "]";
        }
        if ($tractorUsed){
          $howManyFields = \App\Buildings::howManyFields($whichItemType[$actionName] . ' Field', $contractorID);
          if ($howManyFields > 10){
            $howManyFields = 10;
          }
          if ($robot == null){
            $equipmentCaption = \App\Equipment::useEquipped($agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
        }
        for ($i = 0; $i < $howManyFields; $i++){
          $produce = Items::fetchByName($whichItemType[$actionName], $contractorID);
          $field = \App\Buildings::fetchField($whichItemType[$actionName] . ' Field', $contractorID);
          $yield = $field[$whichVarName[$actionName]];
          if ($robot == null){
            $yield = $field[$whichVarName[$actionName]] * $action->rank;
          }
          \App\Buildings::destroy($field->id);
          $user = \App\User::find($contractorID);
          $user->buildingSlots++;
          $user->save();
          $produce->quantity += $yield;
          $produce->save();
          $totalYield += $yield;
        }
        $status = $agentCaption . " harvested " . $totalYield . " "
          . $whichItemType[$actionName] . " from " . $howManyFields . " "
          . $whichItemType[$actionName] . " Field(s). "  . $equipmentCaption . $fuelStatus ;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($produce->quantity) . ".";
        }



      } else if ($actionName == 'hunt'){
        $meatHunted = 2;
        if ($robot == null){
          $meatHunted = $action->rank * 2;
        }
        $meat = Items::fetchByName('Meat', $contractorID);
        $meat->quantity += $meatHunted;
        $meat->save();
        $status = $agentCaption . " hunted " . $meatHunted . " meat. ";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($meat->quantity) . ".";
        }



      } else if ($actionName == 'make-BioMeds'){
        if ($robot != null){
          return;
        }
        $production = $action->rank;
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $herbMeds = \App\Items::fetchByName('HerbMeds', $agentID);
        $bioMaterial = \App\Items::fetchByName('Bio Material', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Bio Lab', $agentID)){
          return [
            'error' => "You need to have a Bio Lab to do this.",
          ];
        } else if ($herbMeds->quantity < 10 ){
          return [
            'error' => "You don't have enough HerbMeds (10 needed) to do this.",
          ];
        } else if ($bioMaterial->quantity < 10 ){
          return [
            'error' => "You don't have enough Bio Material (10 needed) to do this.",
          ];
        } else if ($electricity->quantity < 10 ){
          return [
            'error' => "You don't have enough Electricity (10 needed) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Bio Lab', $agentID);
        $herbMeds->quantity -= 10;
        $herbMeds->save();
        $electricity->quantity -= 10;
        $electricity->save();
        $bioMaterial->quantity -= 10;
        $bioMaterial->save();
        $bioMeds = \App\Items::fetchByName('BioMeds', $agentID);
        $bioMeds->quantity += $production;
        $bioMeds->save();
        $status = $agentCaption . " used 10 Bio Material [" . number_format($bioMaterial->quantity)
        . "],  10 HerbMeds [" . number_format($herbMeds->quantity)
        . "], and 10 Electricity [" . number_format($electricity->quantity) . "] to create "
          . $production . " BioMeds. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($bioMeds->quantity) . ".";
        }




      } else if ($actionName == 'make-book'){
        if ($robot != null){
          return;
        }
        $book = Items::fetchByName('Books', $contractorID);
        $paper = Items::fetchByName('Paper', $agentID);
        $labor = \App\Labor::where('userID', $agentID)->first();
        if ($labor->availableSkillPoints < 1){
          return [
            'error' => $agentCaption . " do not have enough available skill points."
          ];
        } else if ($paper->quantity < 100 ){
          return [
            'error' => $agentCaption . " do not have enough paper."
          ];
        }
        $paper->quantity -= 100;
        $paper->save();
        $book->quantity += $action->rank;
        $book->save();
        $labor->availableSkillPoints--;
        $labor->save();
        $status = "Using 1 available skill point [" . $labor->availableSkillPoints
          . "] and 100 Paper [" . number_format($paper->quantity) . "], you created "
          . $education->rank . " book(s).";
          if ($agentID == $contractorID){
            $status .= " You now have " . number_format($book->quantity) . ".";
          }



      } else if ($actionName == 'make-contract'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $paper = Items::fetchByName('Paper', $agentID);
        if ($paper->quantity < 1){
          return [
            'error' => $agentCaption . " do not have enough Paper. (1 needed)",
          ];
        }
        $paper->quantity--;
        $paper->save();
        $contract = Items::fetchByName('Contracts', $contractorID);
        $contract->quantity += $production;
        $contract->save();
        $status = $agentCaption . " created " . $production
          . " contract(s) from 1 Paper. [" . number_format($paper->quantity) . "] ";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($contract->quantity) . ".";
        }



      } else if ($actionName == 'make-CPU'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }

        $silicon = \App\Items::fetchByName('Silicon', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $copper = \App\Items::fetchByName('Copper Ingots', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('CPU Fabrication Plant', $agentID)){
          return [
            'error' => "You need to have a CPU Fabrication Plant to do this.",
          ];
        } else if ($electricity->quantity < 1000 ){
          return [
            'error' => "You don't have enough Electricity (1000) to do this.",
          ];
        } else if ($silicon->quantity < 100 ){
          return [
            'error' => "You don't have enough Silicon  (100) to do this.",
          ];
        } else if ($copper->quantity < 100 ){
          return [
            'error' => "You don't have enough Copper Ingots (100) to do this.",
          ];
        }
        $silicon->quantity -= 100;
        $silicon->save();
        $copper->quantity -= 100;
        $copper->save();
        $electricity->quantity-=1000;
        $electricity->save();
        $CPU = \App\Items::fetchByName('CPU', $contractorID);
        $CPU->quantity += $production;
        $CPU->save();
        $status = $agentCaption . " used 1000 Electricity ["
          . number_format($electricity->quantity) . "], 100 Silicon [" . number_format($silicon->quantity)
          . "] and 100 Copper [" . number_format($copper->quantity) . "] to create "
          . $production . " CPUs. ";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($CPU->quantity) . ".";
        }


      } else if ($actionName == 'make-diesel-bulldozer' || $actionName == 'make-gasoline-bulldozer'
        || $actionName == 'make-diesel-car' || $actionName == 'make-gasoline-car'
        || $actionName == 'make-diesel-tractor' || $actionName == 'make-gasoline-tractor'){
        $steel = \App\Items::fetchByName('Steel Ingots', $agentID);
        $copper = \App\Items::fetchByName('Copper Ingots', $agentID);
        $tires = \App\Items::fetchByName('Tires', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $engines = \App\Items::fetchByName(ucfirst(explode('-', $actionName)[1]) . ' Engines', $agentID);
        $vehicles = \App\Items::fetchByName(ucfirst(explode('-', $actionName)[2]) . " (" . explode('-', $actionName)[1] . ")", $agentID);
        $requirements = [
          'car' => ['steel' => 50, 'copper'=> 10],
          'bulldozer' => ['steel' => 250, 'copper'=> 50],
          'tractor' => ['steel' => 100, 'copper'=> 20],
        ];
        if (!\App\Buildings::didTheyAlreadyBuildThis('Garage', $agentID)){
          return [
            'error' => $agentCaption . " do not have a Garage built."
          ];
        } else if ($steel->quantity < $requirements[explode('-', $actionName)[2]]['steel']){
          return [
            'error' => $agentCaption . " do not have enough Steel to create this vehicle."
          ];
        } else if ($copper-> quantity < $requirements[explode('-', $actionName)[2]]['copper']){
          return [
            'error' => $agentCaption . " do not have enough Copper to create this vehicle."
          ];
        } else if (explode('-', $actionName)[2] != 'bulldozer' && $tires-> quantity < 4){
          return [
            'error' => $agentCaption . " do not have enough Tires to create this vehicle."
          ];
        } else if ($engines-> quantity < 1){
          return [
            'error' => $agentCaption . " do not have the right engine to create this vehicle."
          ];
        } else if ($electricity-> quantity < 1000){
          return [
            'error' => $agentCaption . " do not have enough Electricity (1000) to create this vehicle."
          ];
        }
        $quantity = 1;
        if ($robot == null){
          $quantity = $action->rank;
        }
        $electricity->quantity -= 1000;
        $electricity->save();
        $steel->quantity -= $requirements[explode('-', $actionName)[2]]['steel'];
        $steel->save();
        $copper->quantity -= $requirements[explode('-', $actionName)[2]]['copper'];
        $copper->save();
        $engines->quantity--;
        $engines->save();
        $vehicles->quantity += $quantity;
        $vehicles->save();
        $tireCaption = "";
        if (explode('-', $actionName)[2] != 'bulldozer'){
          $tires->quantity -= 4;
          $tires->save();
          $tireCaption = " 4 Tires [" . number_format($tires->quantity) . "], ";
        }
        $status = $agentCaption . " used 1000 Electricity ["
          . number_format($electricity->quantity) . "], " . $requirements[explode('-', $actionName)[2]]['steel']
          . " Steel [ " . number_format($steel->quantity) . "], " . $tireCaption
          . $requirements[explode('-', $actionName)[2]]['copper'] . " Copper ["
          . number_format($copper->quantity) . "] and 1 " . explode('-', $actionName)[1]
          . " engine to create " . $quantity . " "
          . ucfirst(explode('-', $actionName)[2]) . " (" . explode('-', $actionName)[1] . ")";
        if ($agentID == $contractorID){
          $status .= " You now have " . $vehicles->quantity . ".";
        }






      } else if ($actionName == 'make-diesel-engine' || $actionName == 'make-gasoline-engine'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $steel = \App\Items::fetchByName('Steel Ingots', $agentID);
        $iron = \App\Items::fetchByName('Iron Ingots', $agentID);      $quantity = 1;
        $copper = \App\Items::fetchByName('Copper Ingots', $agentID);
        $engines = ['make-diesel-engine' => 'Diesel Engines', 'make-gasoline-engine'=>'Gasoline Engines'];
        if ($steel->quantity < 40 || $iron->quantity < 40 || $copper->quantity < 20){
          return [
            'error' => $agentCaption . " do not have enough materials to create this engine."
          ];
        }
        $engineType = $engines[$actionName];
        $steel->quantity -= 40;
        $steel->save();
        $iron->quantity -= 40;
        $iron->save();
        $copper->quantity -= 20;
        $copper->save();
        $enginesBeingCreated = \App\Items::fetchByName($engineType, $contractorID);
        $enginesBeingCreated->quantity += $production;
        $enginesBeingCreated->save();
        $status = $agentCaption . " used 40 Steel [" . number_format($steel->quantity)
          . "], 40 Iron [" . number_format($iron->quantity) . "] and 20 Copper ["
          . number_format($copper->quantity) . "] to create " . $production . " " . $engineType . ". ";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($enginesBeingCreated->quantity) . ".";
        }



      } else if ($actionName == 'make-electric-motor' || $actionName == 'make-gas-motor'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }

        $steel = \App\Items::fetchByName('Steel Ingots', $agentID);
        $iron = \App\Items::fetchByName('Iron Ingots', $agentID);
        $copper = \App\Items::fetchByName('Copper Ingots', $agentID);
        $engines = ['make-electric-motor' => 'Electric Motors', 'make-gas-motor'=>'Gas Motors'];
        if ($steel->quantity < 10 || $iron->quantity < 10 || $copper->quantity < 5){
          return [
            'error' => $agentCaption . " do not have enough materials to create this motor."
          ];
        }
        $engineType = $engines[$actionName];
        $steel->quantity -= 10;
        $steel->save();
        $iron->quantity -= 10;
        $iron->save();
        $copper->quantity -= 5;
        $copper->save();
        $enginesBeingCreated = \App\Items::fetchByName($engineType, $contractorID);
        $enginesBeingCreated->quantity += $production;
        $enginesBeingCreated->save();
        $status = $agentCaption . " used 10 Steel [" . number_format($steel->quantity)
          . "], 10 Iron [" . number_format($iron->quantity) . "] and 5 Copper ["
          . number_format($copper->quantity) . "] to create " . $production . " " . $engineType . ". ";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($enginesBeingCreated->quantity) . ".";
        }




      } else if ($actionName == 'make-electric-jackhammer'
      || $actionName == 'make-gas-jackhammer'
      || $actionName == 'make-electric-chainsaw'
      || $actionName == 'make-gas-chainsaw'){

      $steel = Items::fetchByName('Steel Ingots', $agentID);
      $motors = Items::fetchByName(ucfirst(explode('-', $actionName)[1]) . ' Motors', $agentID);
      $availableTools = [
        'make-electric-jackhammer'=>'Jackhammer (electric)',
        'make-gas-jackhammer'     =>'Jackhammer (gas)',
        'make-electric-chainsaw'  =>'Chainsaw (electric)',
        'make-gas-chainsaw'       =>'Chainsaw (gas)',
      ];
      $tool = Items::fetchByName($availableTools[$actionName], $agentID);
      if ($steel->quantity < 10){
        return [
          'error' => $agentCaption . " do not have enough Steel (need 10) to create this tool."
        ];
      } else if ($motors-> quantity < 1){
        return [
          'error' => $agentCaption . " do not have a motor (need 1) to create this tool."
        ];
      }
      $quantity = 1;
      if ($robot == null){
        $quantity = $action->rank;
      }
      $steel->quantity -= 10;
      $steel->save();
      $motors->quantity -= 1;
      $motors->save();
      $tool->quantity += $quantity;
      $tool->save();
      $status = $agentCaption . " used 10 Steel & 1 "
        . explode('-', $actionName)[1] . " motor to make " . $quantity . " "
        . $availableTools[$actionName] . ".";
      if ($agentID == $contractorID){
        $status .= " You now have " . number_format($tool->quantity) . ".";
      }


      } else if ($actionName == 'make-HerbMed'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }

        $greens = Items::fetchByName('Herbal Greens', $agentID);
        if ($greens->quantity < 10 ){
          return [
            'error' => $agentCaption . " do not have enough Herbal Greens (10 needed) to create HerbMed."
          ];
        }
        $greens->quantity -= 10;
        $greens->save();
        $herbMed = Items::fetchByName('HerbMeds', $contractorID);
        $herbMed->quantity += $production;
        $herbMed->save();
        $status = $agentCaption . " created " . $production
          . " HerbMed(s) from 10 Herbal Greens. [" . number_format($greens->quantity) . "] ";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($herbMed->quantity) . ".";
        }



      } else if ($actionName == 'make-iron-axe' || $actionName == "make-iron-handmill"
        || $actionName == 'make-iron-saw' || $actionName == 'make-iron-pickaxe'
        || $actionName == 'make-stone-axe' || $actionName == "make-stone-handmill"
        || $actionName == 'make-stone-saw' || $actionName == 'make-stone-pickaxe'
        || $actionName == 'make-steel-axe' || $actionName == "make-steel-handmill"
        || $actionName == 'make-steel-pickaxe' || $actionName == 'make-steel-saw'
        || $actionName == 'make-steel-shovel' || $actionName == 'make-iron-shovel'
        || $actionName == 'make-stone-shovel'
      ){

        $material = ucfirst(explode('-', $actionName)[1]);
        $durabilityCaption = ItemTypes::durability(1);
        if ($robot == null){
          if ($toolmakingSpecific->rank > 0){
            $durabilityCaption = ItemTypes::durability($toolmakingSkill->rank);
          }

        }
        $toolType = ItemTypes::where('material', explode('-', $actionName)[1])
          ->where('durability', $durabilityCaption)
          ->where('name', ucfirst(explode('-', $actionName)[2]))->first();
        $item = Items::where('itemTypeID', $toolType->id)
          ->where('userID', $contractorID)->first();
        if ($material != 'Stone'){
          $material .= " Ingots";
        }
        $stone = Items::fetchByName($material, $agentID);
        $wood = Items::fetchByName('Wood', $agentID);
        if ($stone->quantity < 1){
          return [
            'error' => "You do not have enough " . $material . " (1 needed).",
          ];
        } else if ($wood->quantity < 1){
          return [
            'error' => "You do not have enough Wood. (1 needed).",
          ];
        }
        $item->quantity += $action->rank;
        $item->save();
        $stone->quantity -= 1;
        $stone->save();
        $wood->quantity -= 1;
        $wood->save();
        $status = $agentCaption . " used 1 " . explode('-', $actionName)[1] . " [" . number_format($stone->quantity) . "]"
          . " and 1 wood [" . number_format($wood->quantity) . "] to make " . $quantity . " " . $durabilityCaption . " "
          . explode('-', $actionName)[1] . " " . explode('-', $actionName)[2]
          . ". <button id='statusEquipItem-" . $item->id . "' class='equipItem btn btn-link'>[ equip item ]</button>";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($item->quantity) . ".";
        }



      } else if ($actionName == 'make-nanites'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }

        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $silicon = \App\Items::fetchByName('Silicon', $agentID);
        $carbonNanotubes = \App\Items::fetchByName('Carbon Nanotubes', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Nano Lab', $agentID)){
          return [
            'error' => "You need to have a Nano Lab to do this.",
          ];
        } else if ($electricity->quantity < 1000 ){
          return [
            'error' => "You don't have enough Electricity (1000) to do this.",
          ];
        } else if ($silicon->quantity < 100 ){
          return [
            'error' => "You don't have enough Silicon  (100) to do this.",
          ];
        } else if ($carbonNanotubes->quantity < 100 ){
          return [
            'error' => "You don't have enough Carbon Nanotubes (100) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Nano Lab', $agentID);
        $silicon->quantity -= 100;
        $silicon->save();
        $carbonNanotubes->quantity -= 100;
        $carbonNanotubes->save();
        $electricity->quantity -= 1000;
        $electricity->save();
        $nanites = \App\Items::fetchByName('Nanites', $contractorID);
        $nanites->quantity += $production;
        $nanites->save();
        $status = $agentCaption . " used 100 Silicon [" . number_format($silicon->quantity)
          . "], 100 Carbon Nanotubes [" . number_format($carbonNanotubes->quantity)
          . "] and 1000 Electricity [" . number_format($electricity->quantity) . "] to create "
          . $production . " Nanites " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($nanites->quantity) . ".";
        }



      } else if ($actionName == 'make-NanoMeds'){
        if ($robot != null){
          return;
        }

        $production = $action->rank;
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $bioMeds = \App\Items::fetchByName('BioMeds', $agentID);
        $nanites = \App\Items::fetchByName('Nanites', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Nano Lab', $agentID)){
          return [
            'error' => "You need to have a Nano Lab to do this.",
          ];
        } else if ($bioMeds->quantity < 10 ){
          return [
            'error' => "You don't have enough HerbMeds (10 needed) to do this.",
          ];
        } else if ($nanites->quantity < 10 ){
          return [
            'error' => "You don't have enough Bio Material (10 needed) to do this.",
          ];
        } else if ($electricity->quantity < 100 ){
          return [
            'error' => "You don't have enough Electricity (10 0needed) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Nano Lab', $agentID);
        $bioMeds->quantity -= 10;
        $bioMeds->save();
        $electricity->quantity -= 100;
        $electricity->save();
        $nanites->quantity -= 10;
        $nanites->save();
        $nanoMeds = \App\Items::fetchByName('NanoMeds', $agentID);
        $nanoMeds->quantity += $production;
        $nanoMeds->save();
        $status = $agentCaption . " used 10 Nanites [" . number_format($nanites->quantity)
        . "],  10 BioMeds [" . number_format($bioMeds->quantity)
        . "], and 100 Electricity [" . number_format($electricity->quantity) . "] to create "
          . $production . " NanoMeds. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($nanoMeds->quantity) . ".";
        }





      } else if ($actionName == 'make-robot'){

        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $cpu = \App\Items::fetchByName('CPU', $agentID);
        $copper = \App\Items::fetchByName('Copper Ingots', $agentID);
        $steel = \App\Items::fetchByName('Steel Ingots', $agentID);
        $electricMotors = \App\Items::fetchByName('Electric Motors', $agentID);
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Robotics Lab', $agentID)){
          return [
            'error' => "You need to have a Propulsion Lab to do this.",
          ];
        } else if ($cpu->quantity < 10 ){
          return [
            'error' => "You don't have enough CPUs (10 needed) to do this.",
          ];
        } else if ($copper->quantity < 100 ){
          return [
            'error' => "You don't have enough Copper Ingots (100 needed) to do this.",
          ];
        } else if ($steel->quantity < 100 ){
          return [
            'error' => "You don't have enough Steel Ingots (100 needed) to do this.",
          ];
        } else if ($electricity->quantity < 100000 ){
          return [
            'error' => "You don't have enough Electricity (100,000 needed) to do this.",
          ];
        } else if ($electricMotors->quantity < 100 ){
          return [
            'error' => "You don't have enough Electric Motors (100 needed) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Robotics Lab', $agentID);
        $cpu->quantity -= 10;
        $cpu->save();
        $electricity->quantity -= 100000;
        $electricity->save();
        $copper->quantity -= 100;
        $copper->save();
        $steel->quantity -= 100;
        $steel->save();
        $electricMotors->quantity -= 100;
        $electricMotors->save();

        $robots = \App\Items::fetchByName('Robots', $agentID);
        $robots->quantity += $production;
        $robots->save();
        $status = $agentCaption . "  used 100 CPUs [" . number_format($cpu->quantity)
        . "],  100 Copper Ingots [" . number_format($copper->quantity)
        . "], 100 Steel Ingots [" . number_format($steel->quantity)
        . "], 1000 Electricity [" . number_format($electricity->quantity)
        . "], and 100 Electric Motors [" . number_format($electricMotors->quantity)
        . "] to create "
          . $production . " Robots. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($robots->quantity) . ".";
        }




    } else if ($actionName == 'make-rocket-engine'){

      $electricity = \App\Items::fetchByName('Electricity', $agentID);
      $jetFuel = \App\Items::fetchByName('Jet Fuel', $agentID);
      $iron = \App\Items::fetchByName('Iron Ingots', $agentID);
      $steel = \App\Items::fetchByName('Steel Ingots', $agentID);
      $production = 1;
      if ($robot == null){
        $production = $action->rank;
      }
      if (!\App\Buildings::didTheyAlreadyBuildThis('Propulsion Lab', $agentID)){
        return [
          'error' => "You need to have a Propulsion Lab to do this.",
        ];
      } else if ($jetFuel->quantity < 1000 ){
        return [
          'error' => "You don't have enough Jet Fuel (1000 needed) to do this.",
        ];
      } else if ($iron->quantity < 1000 ){
        return [
          'error' => "You don't have enough Iron Ingots (1000 needed) to do this.",
        ];
      } else if ($steel->quantity < 1000 ){
        return [
          'error' => "You don't have enough Steel Ingots (1000 needed) to do this.",
        ];
      } else if ($electricity->quantity < 1000 ){
        return [
          'error' => "You don't have enough Electricity (1000 needed) to do this.",
        ];
      }
      $buildingCaption = \App\Buildings::use('Propulsion Lab', $agentID);
      $jetFuel->quantity -= 1000;
      $jetFuel->save();
      $electricity->quantity -= 1000;
      $electricity->save();
      $iron->quantity -= 1000;
      $iron->save();
      $steel->quantity -= 1000;
      $steel->save();
      $rocketEngines = \App\Items::fetchByName('Rocket Engines', $agentID);
      $rocketEngines->quantity += $production;
      $rocketEngines->save();
      $status = $agentCaption . " used 1000 Jet Fuel [" . number_format($jetFuel->quantity)
      . "],  1000 Iron Ingots [" . number_format($iron->quantity)
      . "], 1000 Steel Ingots [" . number_format($steel->quantity)
      . "], and 100 Electricity [" . number_format($electricity->quantity) . "] to create "
        . $production . " Rocket Engines. " . $buildingCaption;
      if ($agentID == $contractorID){
        $status .= " You now have " . number_format($rocketEngines->quantity) . ".";
      }



      } else if ($actionName == 'make-tire' || $actionName == 'make-radiation-suit'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $rubber = \App\Items::fetchByName('Rubber', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $req = 10;
        $itemName = 'Tires';
        $buildingCaption = "";
        if ($actionName == 'make-radiation-suit'){
          $req = 100;
          $itemName = 'Radiation Suit';
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $agentID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        } else if ($rubber->quantity < $req ){
          return [
            'error' => "You don't have enough Rubber (" . $req . ") to do this.",
          ];
        } else if ($electricity->quantity < $req ){
          return [
            'error' => "You don't have enough Electricity  (" . $req . ") to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $agentID);
        $rubber->quantity -= $req;
        $rubber->save();
        $electricity->quantity -= $req;
        $electricity->save();
        $itemProduced = \App\Items::fetchByName($itemName, $contractorID);
        $itemProduced->quantity += $production;
        $itemProduced->save();
        $status = $agentCaption . "  used " . $req . " Rubber [" . number_format($rubber->quantity)
          . "] and " . $req . " Electricity [" . number_format($electricity->quantity) . "] to create "
          . $production . " " . $itemName . ". " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($itemProduced->quantity) . ".";
        }



      } else if ($actionName == 'make-paper'){
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        $wood = Items::fetchByName('Wood', $agentID);
        $wood->quantity--;
        $wood->save();
        $paper = Items::fetchByName('Paper', $contractorID);
        $paper->quantity += $production;
        $paper->save();
        $status = $agentCaption . " created " . $production
          . " paper from 1 wood. [" . number_format($wood->quantity) . "]";
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($paper->quantity) . ".";
        }




      } else if ($actionName == 'make-satellite'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $cpu = \App\Items::fetchByName('CPU', $agentID);
        $copper = \App\Items::fetchByName('Copper Ingots', $agentID);
        $steel = \App\Items::fetchByName('Steel Ingots', $agentID);
        $solarPanels = \App\Items::fetchByName('Solar Panels', $agentID);
        $rocketEngines = \App\Items::fetchByName('Rocket Engines', $agentID);

        if (!\App\Buildings::didTheyAlreadyBuildThis('Propulsion Lab', $agentID)){
          return [
            'error' => "You need to have a Propulsion Lab to do this.",
          ];
        } else if ($cpu->quantity < 1 ){
          return [
            'error' => "You don't have enough CPUs (1 needed) to do this.",
          ];
        } else if ($copper->quantity < 100 ){
          return [
            'error' => "You don't have enough Copper Ingots (100 needed) to do this.",
          ];
        } else if ($steel->quantity < 100 ){
          return [
            'error' => "You don't have enough Steel Ingots (100 needed) to do this.",
          ];
        } else if ($electricity->quantity < 100 ){
          return [
            'error' => "You don't have enough Electricity (100 needed) to do this.",
          ];
        } else if ($solarPanels->quantity < 5 ){
          return [
            'error' => "You don't have enough Solar Panels (5 needed) to do this.",
          ];
        } else if ($rocketEngines->quantity < 1){
          return [
            'error' => "You don't have enough Rocket Engines (1 needed) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Propulsion Lab', $agentID);
        $cpu->quantity -= 1;
        $cpu->save();
        $electricity->quantity -= 100;
        $electricity->save();
        $copper->quantity -= 100;
        $copper->save();
        $steel->quantity -= 100;
        $steel->save();
        $solarPanels->quantity -= 5;
        $solarPanels->save();
        $rocketEngines->quantity -= 1;
        $rocketEngines->save();
        $satellites = \App\Items::fetchByName('Satellite', $agentID);
        $satellites->quantity += $production;
        $satellites->save();
        $status = $agentCaption . " used 10 CPUs [" . number_format($cpu->quantity)
        . "],  100 Copper Ingots [" . number_format($copper->quantity)
        . "], 100 Steel Ingots [" . number_format($steel->quantity)
        . "], 100 Electricity [" . number_format($electricity->quantity)
        . "], 5 Solar Panels [" . number_format($solarPanels->quantity)
        . "], and 1 Rocket Engines [" . number_format($rocketEngines->quantity)
        . "] to create "
          . $production . " Satellites. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($satellites->quantity) . ".";
        }



      } else if ($actionName == 'make-solar-panel'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $steel = \App\Items::fetchByName('Steel Ingots', $agentID);
        $copper = \App\Items::fetchByName('Copper Ingots', $agentID);
        $silicon = \App\Items::fetchByName('Silicon', $agentID);
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $buildingCaption = "";

        if (!\App\Buildings::didTheyAlreadyBuildThis('Solar Panel Fabrication Plant', $agentID)){
          return [
            'error' => "You need to have a Solar Panel Fabrication Plant to do this.",
          ];
        } else if ($steel->quantity < 100 ){
          return [
            'error' => "You don't have enough Steel (100) to do this.",
          ];
        } else if ($copper->quantity < 100 ){
          return [
            'error' => "You don't have enough Copper (100) to do this.",
          ];
        } else if ($silicon->quantity < 100 ){
          return [
            'error' => "You don't have enough Silicon (100) to do this.",
          ];
        } else if ($electricity->quantity < 100 ){
          return [
            'error' => "You don't have enough Electricity  (100) to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Solar Panel Fabrication Plant', $agentID);
        $steel->quantity -= 100;
        $steel->save();
        $copper->quantity -= 100;
        $copper->save();
        $silicon->quantity -= 100;
        $silicon->save();
        $electricity->quantity -= 100;
        $electricity->save();
        $solarPanels = \App\Items::fetchByName('Solar Panels', $contractorID);
        $solarPanels->quantity += $production;
        $solarPanels->save();
        $status = $agentCaption . " used 100 Electricity [" . number_format($electricity->quantity)
          . "], 1000 Silicon [" . number_format($silicon->quantity) . "], 100 Steel ["
          . number_format($steel->quantity) . "] and 100 Copper [" . number_format($copper->quantity) . "] to create "
          . $production . " Solar Panel(s). " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($solarPanels->quantity) . ".";
        }



      } else if ($actionName == 'mill-flour'){
        $wheat = Items::fetchByName('Wheat', $agentID);
        $flour = Items::fetchByName('Flour', $contractorID);

        if ((($robot == null && !Labor::areTheyEquippedWith('Handmill', $agentID))
          || ($robot != null && !\App\Robot::areTheyEquippedWith('Handmill', $robotID)))
          && !\App\Buildings::didTheyAlreadyBuildThis('Gristmill', $agentID)){
          return ['error' => "You do not have a Handmill or Gristmill"];
        } else if ($wheat->quantity < 10){
          return ['error' => "You do not have enough wheat."];
        }
        $buildingCaption = '';
        $equipmentCaption = '';
        if (\App\Buildings::didTheyAlreadyBuildThis('Gristmill', $agentID) && $wheat->quantity >= 100){
          $modifier = 100;
          $buildingCaption = \App\Buildings::use('Gristmill', $agentID);
        } else {
          if ($robot == null){
            $equipmentCaption = Equipment::useEquipped($agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
          $modifier = 10;
        }
        $flourProduced = $modifier * .5;
        if ($robot == null){
          $flourProduced = $action->rank * ($modifier * .5);
        }
        $wheat->quantity -= $modifier;
        $wheat->save();
        $flour->quantity += $flourProduced ;
        $flour->save();
        $status = $agentCaption . " milled " . $modifier   . " Wheat [" . number_format($wheat->quantity) . "] into " . $flourProduced
          . " Flour. " . $equipmentCaption . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($flour->quantity) . " . ";
        }



      } else if ($actionName == 'mill-log'){
        $logs = Items::fetchByName('Logs', $agentID);
        $wood = Items::fetchByName('Wood', $contractorID);

        if (( ($robot == null && !Labor::areTheyEquippedWith('Saw', $agentID))
          || ($robot != null && !\App\Robot::areTheyEquippedWith('Saw', $robotID)))
          && !\App\Buildings::didTheyAlreadyBuildThis('Sawmill', $agentID)){
          return ['error' => 'You either do not have a Saw equipped or do not have a Sawmill.'];
        }
        $buildingCaption = '';
        $equipmentCaption = '';
        if (\App\Buildings::didTheyAlreadyBuildThis('Sawmill', $agentID) && $logs->quantity >= 10){
          $modifier = 10;
          $buildingCaption = \App\Buildings::use('Sawmill', $agentID);
        } else {
          if ($robot == null){
            $equipmentCaption = Equipment::useEquipped($agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }

          $modifier = 1;
        }
        $woodProduced = 100 * $modifier;
        if ($robot == null){
          $woodProduced = $action->rank * 100 * $modifier;
        }
        $logs->quantity -= $modifier;
        $logs->save();
        $wood->quantity += $woodProduced ;
        $wood->save();
        $status = $agentCaption . " milled " . $modifier   . " log(s) [" . number_format($logs->quantity) . "] into " . $woodProduced
          . " wood. " . $equipmentCaption . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($wood->quantity) . " . ";
        }



      } else if ($actionName == 'mine-sand'){
        //add bulldozer allownace
        $leaseStatus = '';
        $landBonus = \App\Land::count('desert', $agentID);
        $gasoline = Items::fetchByName('Gasoline', $agentID);
        $diesel = Items::fetchByName('Diesel Fuel', $agentID);
        if (!\App\Land::doTheyOwn('desert', $agentID)){
          $currentlyLeasing = \App\Lease::areTheyAlreadyLeasing('desert', $agentID);
          if ($currentlyLeasing){
            $landBonus = 1;
            $leaseStatus = \App\Lease::use('desert', $agentID);
          }
          if ($leaseStatus == false || !$currentlyLeasing){
            return [
              'error' => $agentCaption . " don't have access to any deserts. Sorry."
            ];
          }
        }
        if (($robot == null && !Labor::areTheyEquippedWith('Shovel', $agentID)
        && !Labor::areTheyEquippedWith('Bulldozer (gasoline)', $agentID)
        && !Labor::areTheyEquippedWith('Bulldozer (diesel)', $agentID))
          || ($robot != null
          && !\App\Robot::areTheyEquippedWith('Shovel', $robotID)
          && !\App\Robot::areTheyEquippedWith('Bulldozer (gasoline)', $robotID)
          && !\App\Robot::areTheyEquippedWith('Bulldozer (diesel)', $robotID))
        ){
          return ['error' => 'You do not have anything equipped to mine sand.'];
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Bulldozer (diesel)', $agentID)
          && $diesel->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Bulldozer (diesel)', $robotID)
          && $diesel->quantity < 100)
        ){
          return ['error'
            => 'You have a diesel Bulldozer equipped but do not have enough Diesel Fuel (100).'
          ];
        } else if (($robot == null
          && Labor::areTheyEquippedWith('Bulldozer (gasoline)', $agentID)
          && $gasoline->quantity < 100)
          || ($robot != null
          && \App\Robot::areTheyEquippedWith('Bulldozer (gasoline)', $robotID)
          && $gasoline->quantity < 100)
        ){
          return ['error'
            => 'You have a diesel Bulldozer equipped but do not have enough Diesel Fuel (100).'
          ];
        }
        $buildingCaption = "";
        $modifier = 10;
        if (\App\Buildings::didTheyAlreadyBuildThis('Mine', $agentID)){
          $buildingCaption = \App\Buildings::use('Mine', $agentID);
          $modifier = 100;
        }
        if (($robot == null && !Labor::areTheyEquippedWith('Shovel', $agentID))
          || ($robot != null
          && !\App\Robot::areTheyEquippedWith('Shovel', $robotID))){
          $modifier *= 10;
        }
        $production = $modifier;
        if ($robot == null){
          $equipmentCaption = Equipment::useEquipped($agentID);
          $production = $action->rank * ($modifier + $landBonus);

        } else {
          $equipmentCaption = \App\Robot::useEquipped($robotID);
        }
        $landResource = \App\Land::takeResource('Sand',  $agentID, $production, true);
        if ($landResource != true){
          return $landResource;
        }
        $itemProduced = Items::fetchByName('Sand', $contractorID);
        $itemProduced->quantity += $production;
        $itemProduced->save();
        $fuelStatus = "";
        if (($robot == null && Labor::areTheyEquippedWith('Bulldozer (gasoline)', $agentID))
          || ($robot != null
          && !\App\Robot::areTheyEquippedWith('Bulldozer (gasoline)', $robotID))){
          $gasoline->quantity -= 100;
          $gasoline->save();
          $fuelStatus = " You used 100 Gasoline. [" . number_format($gasoline->quantity) . "] ";
        } else if (($robot == null && Labor::areTheyEquippedWith('Bulldozer (diesel)', $agentID))
          || ($robot != null
          && !\App\Robot::areTheyEquippedWith('Bulldozer (diesel)', $robotID))){
          $diesel->quantity -= 100;
          $diesel->save();
          $fuelStatus = " You used 100 Diesel Fuel. [" . number_format($diesel->quantity) . "] ";
        }
        $status = $agentCaption . " mined " . number_format($production) . " Sand. " . $leaseStatus
        . $equipmentCaption . $fuelStatus . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You  now have " . number_format($itemProduced->quantity) . ". ";
        }



      } else if ($actionName == 'mine-coal' || $actionName == 'mine-iron-ore'
        || $actionName == 'mine-stone' || $actionName == 'mine-copper-ore'
        || $actionName == 'mine-uranium-ore'){
        $electricity = Items::fetchByName('Electricity', $contractorID);
        $gas = Items::fetchByName('Gasoline', $contractorID);
        $leaseStatus = '';
        $landBonus = \App\Land::count('mountains', $agentID);
        if (!\App\Land::doTheyOwn('mountains', $agentID)){
          $currentlyLeasing = \App\Lease::areTheyAlreadyLeasing('mountains', $agentID);
          if ($currentlyLeasing){
            $leaseStatus = \App\Lease::use('mountains', $agentID);
            $landBonus = 1;
          }
          if ($leaseStatus == false || !$currentlyLeasing){
            return [
              'error' => $agentCaption . " don't have access to any mountains. Sorry."
            ];
          }
        }

        if (($robot == null && !Labor::areTheyEquippedWith('Pickaxe', $agentID)
          && !Labor::areTheyEquippedWith('Jackhammer (electric)', $agentID)
          && !Labor::areTheyEquippedWith('Jackhammer (gas)', $agentID))
          || ($robot != null && !\App\Robot::areTheyEquippedWith('Pickaxe', $robotID)
          && !\App\Robot::areTheyEquippedWith('Jackhammer (electric)', $robotID)
          && !\App\Robot::areTheyEquippedWith('Jackhammer (gas)', $robotID)
          )){
          return ['error' => 'You do not have anything equipped to mine.'];
        } else if (Labor::areTheyEquippedWith('Jackhammer (electric)', $agentID) && $electricity->quantity < 100){
          return [
            'error' => $agentCaption . " have an electric Jackhammer equipped but does not have enough Electricity to use it."
          ];
        } else if (Labor::areTheyEquippedWith('Jackhammer (gas)', $agentID) && $gas->quantity < 100){
          return [
            'error' => $agentCaption . " have a gas-powered Jackhammer equipped but does not have enough Gasoline to use it."
          ];
        }
        $buildingCaption = "";
        $modifier = 10;
        if (\App\Buildings::didTheyAlreadyBuildThis('Mine', $agentID)){
          $buildingCaption = \App\Buildings::use('Mine', $agentID);
          $modifier = 100;
        }
        if (($robot == null && !Labor::areTheyEquippedWith('Pickaxe', $agentID))
          || ($robot != null && !Robot::areTheyEquippedWith('Pickaxe', $agentID))
          ){
          $modifier *= 10;
        }
        $miningArr = [
          'mine-coal' => ['item'=>'Coal', 'skill' => 'miningCoal'],
          'mine-iron-ore' => ['item' => 'Iron Ore', 'skill' => 'miningIron'],
          'mine-stone'=> ['item' => 'Stone', 'skill' => 'miningStone'],
          'mine-copper-ore' => ['item'=>'Copper Ore', 'skill' => 'miningCopper'],
          'mine-uranium-ore' => ['item'=>'Uranium Ore', 'skill' => 'miningUranium'],

        ];
        $production = $modifier;
        if ($robot == null){
          $equipmentCaption = Equipment::useEquipped($agentID);
          $production = $action->rank * ($modifier + $landBonus);

        } else {
          $equipmentCaption = \App\Robot::useEquipped($robotID);
        }
        $landResource = \App\Land::takeResource($miningArr[$actionName]['item'],  $agentID, $production, true);
        if ($landResource != true){
          return $landResource;
        }
        $fuelStatus = '';
        if (($robot == null && Labor::areTheyEquippedWith('Jackhammer (electric)', $agentID))
          || ($robot != null && Robot::areTheyEquippedWith('Jackhammer (electric)', $agentID))
          ){
          $electricity->quantity -= 100;
          $electricity->save();
          $fuelStatus = "You used 100 Electricity. [" . number_format($electricity->quantity) . "]";
        } else if (($robot == null && Labor::areTheyEquippedWith('Jackhammer (gas)', $agentID))
          || ($robot != null && Robot::areTheyEquippedWith('Jackhammer (gas)', $agentID))
          ){
          $gas->quantity -= 100;
          $gas->save();
          $fuelStatus = "You used 100 Gasoline. [" . number_format($gas->quantity) . "]";
        }


        $itemProduced = Items::fetchByName($miningArr[$actionName]['item'], $contractorID);
        $itemProduced->quantity += $production;
        $itemProduced->save();
        $status = $agentCaption . " mined " . number_format($production) . " " . $miningArr[$actionName]['item']
          . ". " . $fuelStatus . $equipmentCaption . $buildingCaption . $leaseStatus;
        if ($agentID == $contractorID){
          $status .= " You  now have " . number_format($itemProduced->quantity) . ". " ;
        }
        if ($actionName ==  'mine-uranium-ore' && $robot == null){
          $labor = \App\Labor::where('userID', $agentID)->first();
          $wearingRadiationSuit = false;
          if ($labor->alsoEquipped != null){
            $equipment = \App\Equipment::find($labor->alsoEquipped);
            $equipment->uses--;
            $equipment->save();
            if ($equipment->uses < 1){
              \App\Equipment::destroy($equipment->id);
              $labor = \App\Labor::where('userID', $agentID)->first();
              $labor->alsoEquipped = null;
              $labor->save();
            }
            $itemType = \App\ItemTypes::find($equipment->itemTypeID);
            if ($itemType->name == 'Radiation Suit'){
              $wearingRadiationSuit = true;
            }
          }
          if (!$wearingRadiationSuit ){
            $radStatus = " You weren't wearing a Radiation Suit, so you took a lot of radiation. It's going to take a lot longer to learn things now. ";
            \App\Labor::radPenalty($agentID);
          }
        }



      } else if ($actionName == 'plant-rubber-plantation'){
        $leaseStatus = '';
        $landBonus = \App\Land::count('jungle', $agentID);
        if (!\App\Land::doTheyOwn('jungle', $agentID)){
          $currentlyLeasing = \App\Lease::areTheyAlreadyLeasing('jungle', $agentID);
          if ($currentlyLeasing){
            $landBonus = 1;
            $leaseStatus = \App\Lease::use('jungle', $agentID);
          }
          if ($leaseStatus == false || !$currentlyLeasing){
            return [
              'error' => $agentCaption . " don't have access to any jungles. Sorry."
            ];
          }
        }
        $production = 10;
        if ($robot == null){
          $production = $action->rank * (10 + $landBonus);

        }
        $rubberPlantationType = \App\BuildingTypes::fetchByName('Rubber Plantation');
        $contractor = \App\User::find($contractorID);
        if ($contractor->buildingSlots < 1){
          return ['error' => $contractorCaption . " don't have enough building slots."];
        } else if (!Land::doTheyHaveAccessTo('jungle')){
          return ['error' => "You don't own any jungle."];
        }
        $contractor->buildingSlots--;
        $contractor->save();
        $wheatField = new \App\Buildings;
        $wheatField->buildingTypeID = $rubberPlantationType->id;
        $wheatField->userID = $contractorID;
        $wheatField->rubber = $production;
        $wheatField->harvestAfter = date("Y-m-d H:i:s", strtotime('+24 hours'));
        $wheatField->save();
        $status = $agentCaption . " planted a Rubber Plantation. " . $leaseStatus;



      } else if ($actionName == 'plant-wheat-field' || $actionName == 'plant-plant-x-field' || $actionName == 'plant-herbal-greens-field'){
        $whichSkillName = [
          'plant-wheat-field' => 'farmingWheat',
          'plant-plant-x-field' => 'farmingPlantX',
          'plant-herbal-greens-field'=> 'farmingHerbalGreens'
        ];
        $whichItemType = [
          'plant-wheat-field' => 'Wheat',
          'plant-plant-x-field' => 'Plant X',
          'plant-herbal-greens-field'=> 'Herbal Greens'
        ];
        $whichVarName = [
          'plant-wheat-field' => 'wheat',
          'plant-plant-x-field' => 'plantX',
          'plant-herbal-greens-field'=> 'herbalGreens'
        ];
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        $fieldType = \App\BuildingTypes::fetchByName($whichItemType[$actionName] . ' Field');
        $contractor = \App\User::find($contractorID);
        if ($contractor->buildingSlots < 1){
          return ['error' => $contractorCaption . " don't have enough building slots."];
        }
        $contractor->buildingSlots--;
        $contractor->save();
        $field = new \App\Buildings;
        $field->buildingTypeID = $fieldType->id;
        $field->userID = $contractorID;
        $field[$whichVarName[$actionName]] = $production;
        $field->harvestAfter = date("Y-m-d H:i:s", strtotime('+24 hours'));
        $field->save();
        $status = $agentCaption . " planted a " . $whichItemType[$actionName] . " Field.";



      } else if ($actionName == 'pump-oil'){
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Oil Well', $agentID)){
          return ['error' => "You do not have an Oil Well. Please build one first."];
        } else if ($electricity->quantity < 10){
          return ['error' => "You need to have at least 10 Electricity to pump oil."];
        }
        $buildingCaption = \App\Buildings::use('Oil Well', $agentID);
        $oilProduced = 10;
        if ($robot == null){
          $oilProduced = $action->rank * 10;
        }
        $landResource = \App\Land::takeResource('Oil',  $agentID, $oilProduced, true);
        if ($landResource != true){
          return $landResource;
        }
        $electricity->quantity -= 10;
        $electricity->save();
        $oil = \App\Items::fetchByName ('Oil', $contractorID);
        $oil->quantity += $oilProduced;
        $oil->save();
        $status = $agentCaption . " used 10 Electricity [" . number_format($electricity->quantity)
        . "] to pump " . $oilProduced . " Oil. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($oil->quantity) . ". ";
        }



      } else if ($actionName == 'refine-oil'){
        if ($robot == null){
          return;
        }
        $electricity = \App\Items::fetchByName('Electricity', $agentID);
        $oil = \App\Items::fetchByName ('Oil', $agentID);

        if (!\App\Buildings::didTheyAlreadyBuildThis('Oil Refinery', $agentID)){
          return ['error' => "You do not have an Oil Refinery. Please build one first."];
        } else if ($electricity->quantity < 100){
          return ['error' => "You need to have at least 100 Electricity to refine oil."];
        } else if ($oil->quantity < 100){
          return ['error' => "You need to have at least 100 Oil to refine oil."];
        }
        $buildingCaption = \App\Buildings::use('Oil Refinery', $agentID);

        $refineryYield = $action->rank;
        $electricity->quantity -= 100;
        $electricity->save();
        $oil->quantity -= 100;
        $oil->save();
        $jetFuel  = \App\Items::fetchByName ('Jet Fuel', $contractorID);
        $jetFuel->quantity += 1 *$refineryYield;
        $jetFuel->save();
        $gasoline = \App\Items::fetchByName ('Gasoline', $contractorID);
        $gasoline->quantity += 5 * $refineryYield;
        $gasoline->save();
        $diesel   = \App\Items::fetchByName ('Diesel Fuel', $contractorID);
        $diesel->quantity += 4 * $refineryYield;
        $diesel->save();
        $status = $agentCaption . " used 100 Electricity [" . number_format($electricity->quantity)
          . "] and 100 Oil [" . number_format($oil->quantity) . "] to refine " . ( 1 * $refineryYield )
          . " Jet Fuel, " . (4 * $refineryYield ) . " Diesel Fuel,  and "
          . (5 * $refineryYield) . " Gasoline. " . $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($jetFuel->quantity) . " Jet Fuel, "
            . number_format($gasoline->quantity) . " Gasoline and " . number_format($diesel->quantity) . " Diesel.";
        }



      } else if ($actionName == 'smelt-copper'){
        $coal = Items::fetchByName('Coal', $agentID);
        $electricity = Items::fetchByName('Electricity', $contractorID);

        $copperOre = Items::fetchByName('Copper Ore', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)){
          return ['error' => "You don't have a Small Furnace, Large Furnace or Electric Arc Furnace."];
        } else if ($copperOre->quantity < 10){
          return ['error' => "You don't have enough Copper Ore."];
        } else if(!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
        && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)
        && \App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        && ($electricity->quantity < 1000 || $copperOre->quantity < 1000)){
          return ['error' => "You have an Electric Arc Furnace but don't have enough Electricity or Copper Ore."];
        } else if ((!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        || (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        && ($copperOre->quantity < 1000 || $electricity->quantity < 1000)))
        && (\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
        || \App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)) && $coal->quantity < 10){
          return ['error' => "You have a Small Furnace  or Large Furnace but don't have enough Coal."];
        }
        $buildingCaption = '';

        $copperIngots = Items::fetchByName('Copper Ingots', $contractorID);
        $buildingName = 'Small Furnace';
        $modifier = 10;
        $productionModifier = 1;
        if (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
          && $electricity->quantity >= 1000 && $copperOre->quantity >= 1000){
            $modifier = 1000;
            $productionModifier = 100;
            $buildingName = 'Electric Arc Furnace';
        } else if (\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)){
          if ($copperOre->quantity >= 100 && $coal->quantity >= 100){
            $modifier = 100;
            $productionModifier = 10;
            $buildingName = 'Large Furnace';
          }
          if (!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)){
            $buildingName = 'Large Furnace';
          }
        }
        $production = $productionModifier;
        if ($robot == null){
          $production = $action->rank  * $productionModifier;
        }
        $buildingCaption = \App\Buildings::use($buildingName, $agentID);
        $copperOre->quantity -= $modifier;
        $copperOre->save();
        $copperIngots->quantity += $production;
        $copperIngots->save();
        if ($buildingName == 'Electric Arc Furnace'){
          $electricity->quantity -= $modifier;
          $electricity->save();
          $status = $agentCaption . " used " . $modifier . " Electricity [" . number_format($electricity->quantity) . "] and "
            . $modifier . " copper ore [" . number_format($copperOre->quantity) . "] to smelt " . $production
            . " copper ingots. ";
        } else {
          $coal->quantity -= $modifier;
          $coal->save();
          $status = $agentCaption . " used " . $modifier . " coal [" . number_format($coal->quantity) . "] and "
            . $modifier . " copper ore [" . number_format($copperOre->quantity) . "] to smelt " . $production
            . " copper ingots. ";
        }
        $status .= $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($copperIngots->quantity) . ". ";
        }



      } else if ($actionName == 'smelt-iron'){
        $coal = Items::fetchByName('Coal', $agentID);
        $electricity = Items::fetchByName('Electricity', $contractorID);
        $ironOre = Items::fetchByName('Iron Ore', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)){
          return ['error' => "You don't have a Small Furnace, Large Furnace or Electric Arc Furnace."];
        } else if ($ironOre->quantity < 10){
          return ['error' => "You don't have enough Iron Ore."];
        } else if(!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
        && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)
        && \App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        && ($electricity->quantity < 1000 || $ironOre->quantity < 1000)){
          return ['error' => "You have an Electric Arc Furnace but don't have enough Electricity or Iron Ore."];
        } else if ((!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        || (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        && ($ironOre->quantity < 1000 || $electricity->quantity < 1000)))
        && (\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
        || \App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)) && $coal->quantity < 10){
          return ['error' => "You have a Small Furnace  or Large Furnace but don't have enough Coal."];
        }
        $buildingCaption = '';
        $ironIngots = Items::fetchByName('Iron Ingots', $contractorID);
        $buildingName = 'Small Furnace';
        $modifier = 10;
        $productionModifier = 1;
        if (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
          && $electricity->quantity >= 1000 && $ironOre->quantity >= 1000){
            $modifier = 1000;
            $productionModifier = 100;
            $buildingName = 'Electric Arc Furnace';
        } else if (\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)){
          if ($ironOre->quantity >= 100 && $coal->quantity >= 100){
            $modifier = 100;
            $productionModifier = 10;
            $buildingName = 'Large Furnace';
          }
          if (!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)){
            $buildingName = 'Large Furnace';
          }
        }
        $production = $productionModifier;
        if ($robot == null){
          $production = $action->rank  * $productionModifier;
        }
        $buildingCaption = \App\Buildings::use($buildingName, $agentID);
        $ironOre->quantity -= $modifier;
        $ironOre->save();
        $ironIngots->quantity += $production;
        $ironIngots->save();
        if ($buildingName == 'Electric Arc Furnace'){
          $electricity->quantity -= $modifier;
          $electricity->save();
          $status = $agentCaption . " used " . $modifier . " Electricity [" . number_format($electricity->quantity) . "] and "
            . $modifier . " iron ore [" . number_format($ironOre->quantity) . "] to smelt " . $production
            . " iron ingots. ";
        } else {
          $coal->quantity -= $modifier;
          $coal->save();
          $status = $agentCaption . " used " . $modifier . " coal [" . number_format($coal->quantity) . "] and "
            . $modifier . " iron ore [" . number_format($ironOre->quantity) . "] to smelt " . $production
            . " iron ingots. ";
        }
        $status .= $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($ironIngots->quantity) . ". ";
        }



      } else if ($actionName == 'smelt-steel'){
        $coal = Items::fetchByName('Coal', $agentID);
        $electricity = Items::fetchByName('Electricity', $contractorID);
        $ironIngots = Items::fetchByName('Iron Ingots', $agentID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)){
          return ['error' => "You don't have a Small Furnace, Large Furnace or Electric Arc Furnace."];
        } else if ($ironIngots->quantity < 10){
          return ['error' => "You don't have enough Iron Ingots."];
        } else if(!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
        && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)
        && \App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        && ($electricity->quantity < 1000 || $ironIngots->quantity < 1000)){
          return ['error' => "You have an Electric Arc Furnace but don't have enough Electricity or Iron Ingots."];
        } else if ((!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        || (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
        && ($ironIngots->quantity < 1000 || $electricity->quantity < 1000)))
        && (\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)
        || \App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)) && $coal->quantity < 10){
          return ['error' => "You have a Small Furnace  or Large Furnace but don't have enough Coal."];
        }
        $buildingCaption = '';
        $steelIngots = Items::fetchByName('Steel Ingots', $contractorID);
        $buildingName = 'Small Furnace';
        $modifier = 10;
        $productionModifier = 1;
        if (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $agentID)
          && $electricity->quantity >= 1000 && $ironIngots->quantity >= 1000){
            $modifier = 1000;
            $productionModifier = 100;
            $buildingName = 'Electric Arc Furnace';
        } else if (\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $agentID)){
          if ($ironIngots->quantity >= 100 && $coal->quantity >= 100){
            $modifier = 100;
            $productionModifier = 10;
            $buildingName = 'Large Furnace';
          }
          if (!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $agentID)){
            $buildingName = 'Large Furnace';
          }
        }
        $production = $productionModifier;
        if ($robot == null){
          $production = $action->rank  * $productionModifier;
        }
        $buildingCaption = \App\Buildings::use($buildingName, $agentID);
        $ironIngots->quantity -= $modifier;
        $ironIngots->save();
        $steelIngots->quantity += $production;
        $steelIngots->save();
        if ($buildingName == 'Electric Arc Furnace'){
          $electricity->quantity -= $modifier;
          $electricity->save();
          $status = $agentCaption . " used " . $modifier . " Electricity [" . number_format($electricity->quantity) . "] and "
            . $modifier . " iron ingots [" . number_format($ironIngots->quantity) . "] to smelt " . $production
            . " steel ingots. ";
        } else {
          $coal->quantity -= $modifier;
          $coal->save();
          $status = $agentCaption . " used " . $modifier . " coal [" . number_format($coal->quantity) . "] and "
            . $modifier . " iron ingots [" . number_format($ironIngots->quantity) . "] to smelt " . $production
            . " steel ingots. ";
        }
        $status .= $buildingCaption;
        if ($agentID == $contractorID){
          $status .= " You now have " . number_format($steelIngots->quantity) . ". ";
        }



      } else if ($actionName == 'transfer-electricity-from-solar-power-plant'){
        if (!\App\Buildings::didTheyAlreadyBuildThis('Solar Power Plant', $contractorID)){
          return ['error' => 'You do not have a Solar Power Plant.'];
        }
        $powerPlant = \App\Buildings::fetchByName('Solar Power Plant', $contractorID);
        if ($powerPlant->electricity < 1){
          return ['error' => "There's no Electricity in your Solar Power Plant."];
        }
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $production *= $powerPlant->electricity;
        $powerPlant->electricity = 0;
        $powerPlant->save();
        $buildingCaption = \App\Buildings::use('Solar Power Plant', $contractorID);
        $electricity->quantity += $production;
        $electricity->save();
        $status = $agentCaption . " transferred " . $production . " Electricity [" . number_format($electricity->quantity) . "] from your Solar Power Plant. " . $buildingCaption;



      }
      if ($robot == null){
        //removed work hours decrement
        $user = \App\User::find($agentID);
        $user->lastAction = date("Y-m-d H:i:s");
        $user->save();
      }
      $medStatus = \App\Labor::useMeds($agentID);
      $childrenStatus = \App\Labor::feedChildren($agentID);
      return ['status' => $status . $medStatus . $radStatus . " " . $childrenStatus];
    }



    public static function doTheyHaveEnoughToBuild($buildingName){
      $buildingCosts = \App\BuildingTypes::fetchBuildingCost($buildingName);
      foreach ($buildingCosts as $material => $cost){
        $item = Items::fetchByName($material, Auth::id());
        if($item->quantity < $cost){
          return false;
        }
      }
      return true;
    }

    public static function fetchAvailableBuildings(){
      $buildingTypes = \App\BuildingTypes::fetch();
      $availableBuildings = [];
      foreach($buildingTypes as $buildingType){
        if (Actions::doTheyHaveEnoughToBuild($buildingType->name)
          && !\App\Buildings::doesItExist($buildingType->name, Auth::id())){
          $availableBuildings[] = $buildingType->name;
        }
      }
      return $availableBuildings;
    }

    public static function fetchRobotActions(){
      $bannedActions = \App\Robot::fetchBannedActions();
      $robots = \App\Robot::fetch();
      $actionList = \App\Actions::list();
      $robotActions = [];
      foreach ($robots as $robot){
        $skillType = \App\SkillTypes::find($robot->skillTypeID);
        $skilledActions = [];
        foreach (\App\Actions::list() as $actionName => $skillTypeIdentifier){
          if ($skillTypeIdentifier == $skillType->identifier && !in_array($actionName, $bannedActions)){
            $skilledActions [] = $actionName;
          }
        }
        $robotActions[$skillType->id] = $skilledActions;
      }
      return $robotActions;
    }

    public static function list(){
      return [
        "chop-tree" => 'lumberjacking',
        "cook-meat" => 'cooking',
        "cook-flour" => 'cooking', //
        'convert-corpse-to-genetic-material' => 'engineering',
        'convert-corpse-to-Bio-Material' => 'engineering',
        'convert-herbal-greens-to-Bio-Material' => 'engineering',
        'convert-plant-x-to-Bio-Material' => 'engineering',
        'convert-meat-to-Bio-Material' => 'engineering',
        'convert-wheat-to-Bio-Material' => 'engineering',
        'convert-coal-to-carbon-nanotubes' => 'engineering',
        'convert-sand-to-silicon' => 'engineering',
        'convert-wood-to-carbon-nanotubes' => 'engineering',
        'convert-wood-to-coal'                  => 'engineering', //untested 04/13/22

        'convert-uranium-ore-to-plutonium' => 'engineering',
        "explore" => 'exploring',
        "gather-stone" => null, //
        "gather-wood" => null, //
        'generate-electricity-with-coal' => 'engineering',
        'generate-electricity-with-plutonium' => 'engineering',
        'harvest-herbal-greens' => 'farming',
        'harvest-plant-x' => 'farming',
        'harvest-rubber' => 'farming',
        'harvest-wheat' => 'farming',
        "hunt" => 'hunting', //
        'make-BioMeds' => 'engineering',
        'make-book' => 'education',
        'make-contract' => 'contracting',
        'make-CPU' => 'engineering',
        'make-diesel-bulldozer' => 'engineering',
        'make-diesel-car' => 'engineering',
        'make-diesel-tractor' => 'engineering',
        'make-diesel-engine' => 'machining',
        'make-electric-motor' => 'machining',
        'make-electric-chainsaw' => 'toolmaking',
        'make-electric-jackhammer' => 'toolmaking',
        'make-gasoline-engine' => 'machining',
        'make-gas-motor' => 'machining',
        'make-gas-chainsaw' => 'toolmaking',
        'make-gas-jackhammer' => 'toolmaking',
        'make-gasoline-bulldozer' => 'engineering',
        'make-gasoline-car' => 'engineering',
        'make-gasoline-tractor' => 'engineering',
        'make-HerbMed' => 'medicine',
        "make-iron-axe" => 'toolmaking',
        "make-iron-handmill" => 'toolmaking',
        "make-iron-pickaxe" => 'toolmaking',
        "make-iron-saw" => 'toolmaking',
        "make-iron-shovel" => 'toolmaking',
        'make-NanoMeds' => 'medicine',
        'make-nanites' => 'nanotechnology',
        'make-paper' => 'papermaking',
        'make-rocket-engine' => 'engineering',
        'make-solar-panel' => 'engineering',
        "make-steel-axe" => 'toolmaking',
        "make-steel-handmill" => 'toolmaking',
        "make-steel-pickaxe" => 'toolmaking',
        "make-steel-saw" => 'toolmaking',
        "make-steel-shovel" => 'toolmaking',
        "make-stone-axe" => 'toolmaking',
        "make-stone-handmill" => 'toolmaking',
        "make-stone-pickaxe" => 'toolmaking',
        "make-stone-saw" => 'toolmaking',
        "make-stone-shovel" => 'toolmaking',
        'make-tire' => 'chemicalEngineering',
        'make-radiation-suit' => 'engineering',
        'make-robot'          => 'robotics',
        'make-satellite'      => 'engineering',
        "mill-flour" => 'flourMilling', //
        "mill-log" => 'sawmilling', //
        'mine-coal' => 'mining',
        'mine-copper-ore' => 'mining',
        'mine-iron-ore' => 'mining',
        "mine-sand" => 'mining', //
        "mine-stone" => 'mining', //
        'mine-uranium-ore' => 'mining',
        'plant-herbal-greens-field' => 'farming',
        'plant-plant-x-field' => 'farming',
        'plant-rubber-plantation' => 'farming',
        'plant-wheat-field' => 'farming',
        'pump-oil' => 'engineering',
        'refine-oil' => 'engineering',
        'smelt-copper' => 'smelting',
        "smelt-iron" => 'smelting',
        'smelt-steel' => 'smelting',
        'transfer-electricity-from-solar-power-plant' => 'engineering',
      ];
    }

    public static function possible(){
      $actions = \App\Actions::list();
      $possibleActions = [];
      foreach ($actions as $action=>$skillIdentifier){
        if ($skillIdentifier != null){
          $skill = \App\Skills::fetchByIdentifier($skillIdentifier, Auth::id());
          if ($skill->rank > 0){
            $possibleActions [] = $action;
          }
        } else {
          $possibleActions [] = $action;
        }
      }
      return $possibleActions;
    }
}