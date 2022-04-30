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
        'buildings' => \App\Actions::fetchAvailableBuildings(),
        'possible'  =>\App\Actions::fetchActionable($userID),
        'robots'    => \App\Actions::fetchRobotActions(),
        'unlocked'  =>\App\Actions::fetchUnlocked($userID),
      ];
  }

  public static function fetchUnlocked($userID){
    return \App\Actions
      ::join('action_types', 'actions.actionTypeID', 'action_types.id')
      ->where('userID', $userID)->where('unlocked', true)
      ->select('name', 'actions.id', 'actionTypeID', 'totalUses', 'nextRank',
      'rank', 'unlocked')->get();
  }



    public static function fetchActionable($userID){
      $actionable = [];
      $labor = \App\Labor::where('userID', $userID)->first();
      $wearingRadiationSuit = false;
      if ($labor->alsoEquipped != null){
        $equipment = \App\Equipment::find($labor->alsoEquipped);
        $itemType = \App\ItemTypes::find($equipment->itemTypeID);
        if ($itemType->name == 'Radiation Suit'){
          $wearingRadiationSuit = true;
        }
      }
      $solarElectricity = 0;
      if (\App\Buildings::doTheyHaveAccessTo('Solar Power Plant', $userID)){
        $solarPowerPlant = \App\Buildings::fetchByName('Solar Power Plant', $userID );
        $solarElectricity = $solarPowerPlant->electricity;
      }
      foreach (\App\ActionTypes::all() as $action){
        $reqBuildings = \App\Buildings
          ::whichBuildingsDoTheyHaveAccessTo($action->name, \Auth::id());
        $coveredActions = ['chop-tree', 'cook-meat', 'cook-flour',
          'harvest-herbal-greens', 'harvest-plant-x', 'harvest-wheat',
          'harvest-rubber', 'make-book', 'mill-flour', 'mill-log', 'mine-sand',
          'mine-coal', 'mine-stone', 'mine-iron-ore', 'mine-copper-ore',
          'mine-uranium-ore', 'plant-rubber-plantation', 'plant-wheat-field',
          'plant-herbal-greens-field', 'plant-plant-x-field', 'program-robot',
          'smelt-copper', 'smelt-iron', 'smelt-steel',
          'transfer-electricity-from-solar-power-plant'
        ];
        if ($action->name == 'chop-tree'
          && count(\App\Equipment::whichOfTheseCanTheyUse(['Chainsaw (electric)',
          'Chainsaw (gasoline)', 'Axe'], \Auth::id())) > 0
          && Land::doTheyHaveAccessTo('forest')){
          $actionable[] = $action->name;

        } else if ($action->name == 'cook-meat'
          && (\App\Items::doTheyHave('Meat', 1)
            && \App\Items::doTheyHave('Wood', 1)
            && \App\Buildings::doTheyHaveAccessTo('Campfire', Auth::id()))
          || (\App\Items::doTheyHave('Meat', 10)
            && \App\Items::doTheyHave('Wood', 10)
            && \App\Buildings::doTheyHaveAccessTo('Kitchen', Auth::id()))
          || (\App\Items::doTheyHave('Meat', 100)
            &&  \App\Items::doTheyHave('Electricity', 100)
            && \App\Buildings::doTheyHaveAccessTo('Food Factory', Auth::id()))){
          $actionable[] = $action->name;

        } else if ($action->name == 'cook-flour'
        && ((\App\Items::doTheyHave('Flour', 1)
          && \App\Items::doTheyHave('Wood', 1)
          && \App\Buildings::doTheyHaveAccessTo('Campfire', Auth::id()))
        || (\App\Items::doTheyHave('Flour', 10)
          && \App\Items::doTheyHave('Wood', 10)
          && \App\Buildings::doTheyHaveAccessTo('Kitchen', Auth::id()))
        || (\App\Items::doTheyHave('Flour', 100)
          &&  \App\Items::doTheyHave('Electricity', 100)
          && \App\Buildings::doTheyHaveAccessTo('Food Factory', Auth::id())))){
          $actionable[] = $action->name;

        } else if ($action->name == 'harvest-herbal-greens'
          && \App\Buildings::canTheyHarvest('Herbal Greens Field', Auth::id())){
          $actionable[] = $action->name;

        } else if ($action->name == 'harvest-plant-x'

          && \App\Buildings::canTheyHarvest('Plant X Field', Auth::id())){
          $actionable[] = $action->name;

        } else if ($action->name == 'harvest-wheat'
          && \App\Buildings::canTheyHarvest('Wheat Field', Auth::id())){
          $actionable[] = $action->name;

        } else if ($action->name == 'harvest-rubber'
          && \App\Buildings::canTheyHarvest('Rubber Plantation', Auth::id())){
          $actionable[] = $action->name;

        } else if ($action->name == 'make-book'
          &&  \App\Items::doTheyHave('Paper', 100)
          && $labor->availableSkillPoints < 1){
          $actionable[] = $action->name;

        } else if ($action->name == 'mill-flour'
        && (Equipment::doTheyHave('Handmill', Auth::id())
          && Items::doTheyHave('Flour', 10))
          || (\App\Buildings::doTheyHaveAccessTo('Gristmill', Auth::id())
          && Items::doTheyHave('Flour', 100))){
          $actionable[] = $action->name;

        } else if ($action->name == 'mill-log'
          && (Equipment::doTheyHave('Saw', Auth::id())
            && Items::doTheyHave('Logs', 1))
            || (\App\Buildings::doTheyHaveAccessTo('Sawmill', Auth::id())
            && Items::doTheyHave('Logs', 10))){
          $actionable[] = $action->name;

        } else if (($action->name == 'mine-sand')
          && Land::doTheyHaveAccessTo('desert')
          && count(\App\Equipment::whichOfTheseCanTheyUse(['Bulldozer (gasoline)',
          'Bulldozer (diesel)', 'Shovel'], \Auth::id())) > 0){
          $actionable[] = $action->name;

        } else if (($action->name == 'mine-coal' || $action->name == 'mine-stone'
          || $action->name == 'mine-iron-ore' || $action->name == 'mine-copper-ore')
          && Land::doTheyHaveAccessTo('mountains')
          && count(\App\Equipment::whichOfTheseCanTheyUse(['Jackhammer (electric)',
          'Jackhammer (gasoline)', 'Pickaxe'], \Auth::id())) > 0){
          $actionable[] = $action->name;

        } else if ($action->name == 'mine-uranium-ore'
          && Land::doTheyHaveAccessTo('mountains')
          && count(\App\Equipment::whichOfTheseCanTheyUse(['Jackhammer (electric)',
          'Jackhammer (gasoline)', 'Pickaxe'], \Auth::id())) > 0
          && $wearingRadiationSuit){
          $actionable[] = $action->name;

        } else if ($action->name == 'plant-rubber-plantation'
          && \App\User::find(Auth::id())->buildingSlots>0
          && Land::doTheyHaveAccessTo('jungle')){
          $actionable[] = $action->name;

        } else if (($action->name == 'plant-wheat-field'
        || $action->name == 'plant-herbal-greens-field'
        || $action->name == 'plant-plant-x-field')
          && \App\User::find(Auth::id())->buildingSlots>0){
          $actionable[] = $action->name;

        } else if ($action->name == 'program-robot'
          && \App\Items::doTheyHave('Robots', 1)){
          $actionable[] = $action->name;

        } else if ($action->name == 'smelt-copper'
        && ((\App\Buildings::doTheyHaveAccessTo('Electric Arc Furnace', Auth::id())
          && \App\Items::doTheyHave('Copper Ore', 1000)
          && \App\Items::doTheyHave('Electricity', 1000))
        || (\App\Buildings::doTheyHaveAccessTo('Small Furnace', Auth::id())
          && \App\Items::doTheyHave('Copper Ore', 10)
          && \App\Items::doTheyHave('Coal', 10))
        || (\App\Buildings::doTheyHaveAccessTo('Large Furnace', Auth::id())
          && \App\Items::doTheyHave('Copper Ore', 100)
          && \App\Items::doTheyHave('Coal', 100)))){
          $actionable[] = $action->name;
          $actionable[] = $action->name;

        } else if ($action->name == 'smelt-iron'
          && ((\App\Buildings::doTheyHaveAccessTo('Electric Arc Furnace', Auth::id())
            && \App\Items::doTheyHave('Iron Ore', 1000)
            && \App\Items::doTheyHave('Electricity', 1000))
          || (\App\Buildings::doTheyHaveAccessTo('Small Furnace', Auth::id())
            && \App\Items::doTheyHave('Iron Ore', 10)
            && \App\Items::doTheyHave('Coal', 10))
          || (\App\Buildings::doTheyHaveAccessTo('Large Furnace', Auth::id())
            && \App\Items::doTheyHave('Iron Ore', 100)
            && \App\Items::doTheyHave('Coal', 100)))){
          $actionable[] = $action->name;

        } else if ($action->name == 'smelt-steel'
        && ((\App\Buildings::doTheyHaveAccessTo('Electric Arc Furnace', Auth::id())
          && \App\Items::doTheyHave('Iron Ingots', 1000)
          && \App\Items::doTheyHave('Electricity', 1000))
        || (\App\Buildings::doTheyHaveAccessTo('Small Furnace', Auth::id())
          && \App\Items::doTheyHave('Iron Ingots', 10)
          && \App\Items::doTheyHave('Coal', 10))
        || (\App\Buildings::doTheyHaveAccessTo('Large Furnace', Auth::id())
          && \App\Items::doTheyHave('Iron Ingots', 100)
          && \App\Items::doTheyHave('Coal', 100)))){
          $actionable[] = $action->name;

        } else if ($action->name == 'transfer-electricity-from-solar-power-plant'
          && \App\Buildings::doTheyHaveAccessTo('Solar Power Plant', Auth::id())
          && $solarElectricity > 0){
          $actionable[] = $action->name;

        } else if (!in_array($action->name, $coveredActions) && ($reqBuildings === null
          || ($reqBuildings !== null && count($reqBuildings) > 0))
          && \App\Items::doTheyHaveEnoughFor($action->name)){
          $actionable[] = $action->name;
        }

      }
      return $actionable;

    }

    public static function fetchByName($userID, $name){
      $actionType = \App\ActionTypes::where('name', $name)->first();
      return \App\Actions::where('actionTypeID', $actionType->id)
        ->where('userID', $userID)->first();

    }


    public static function do($actionName, $agentID, $contractorID, $robotID){
      \App\Metric::newAction($agentID, $actionName);
      $action = \App\Actions::fetchByName($agentID, $actionName);
      if (!$action->unlocked || $action->rank == 0){
        return [
          'error' => "This action hasn't been unlocked yet.",
        ];
      }
      $resourceUserID = $agentID;
      $buildingUserID = $agentID;
      if ($agentID != $contractorID){
        $resourceUserID = $contractorID;
        $buildingUserID = $contractorID;
      }
      $status = "";
      $contractorCaption = " You ";
      $agentCaption = " You ";
      $radiationPoisoning = false;
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
      } else {
        \App\Labor::doAction($agentID, $action->id);
      }
      if ($robot == null && $agentID == $contractorID && strtotime('now') - strtotime(\App\User::find($agentID)->lastAction) == 0){
        return [
          'error' => "Sorry, you're doing this too often.",
        ];
      }



      if ($actionName == 'chop-tree'){
        $leaseStatus = '';
        $equipmentCaption = '';
        $landBonus = \App\Land::count('forest', $agentID);
        $equipmentAvailable = \App\Equipment
          ::whichOfTheseCanTheyUse(['Chainsaw (electric)', 'Chainsaw (gasoline)', 'Axe'], $agentID);

        if (count($equipmentAvailable) == 0){
          return [
            'error' => $agentCaption . " do not have any equipment that can be used to chop down a tree."
          ];
        }
        if (!\App\Land::doTheyOwn('forest', $contractorID)){
          $currentlyLeasing = \App\Lease::areTheyAlreadyLeasing('forest', $contractorID);
          if ($currentlyLeasing){

            $landBonus = 1;
            $leaseStatus = \App\Lease::use('forest', $contractorID);
          }
          if ($leaseStatus == false || !$currentlyLeasing){
            return [
              'error' => $agentCaption . " don't have access to any Forests. Sorry."
            ];
          }
        }
        $baseChop = 10;
        if (($robot == null && $equipmentAvailable[0] == 'Axe')
          || ($robot != null && \App\Robot::areTheyEquippedWith('Axe', $robotID))){
          $baseChop = 1;
        }
        if ($robot == null){
          $equipmentCaption = Equipment::useEquipped($equipmentAvailable[0], $agentID);
          if (!$equipmentCaption){
            return [
              'error' => "Something went wrong with an equipment check. Sorry."
            ];
          }
          $production = $action->rank * $baseChop * $landBonus;
        } else {
          $equipmentCaption = \App\Robot::useEquipped($robotID);
          $production = $baseChop;
        }
        $landResource = \App\Land::takeResource('Logs',  $agentID, $production, true);
        if ($landResource != true){
          return $landResource;
        }
        $output = \App\Items::make('Logs', $production, $contractorID, $agentID);
        if ($agentID == $contractorID){
          $status = "<span class='actionInput'>" . $equipmentCaption . $leaseStatus . "</span> &rarr; ";
        }
        $status .= $output;




      } else if ($actionName == 'convert-corpse-to-genetic-material'){
        $corpse = \App\Items::fetchByName('Corpse', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Clone Vat', $contractorID)){
          return [
            'error' => "You need to have a Clone Vat to do this.",
          ];
        }
        $production = 100;
        if ($robot == null){
          $production = $action->rank * 100;
        }
        $buildingCaption = \App\Buildings::use('Clone Vat', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Genetic Material', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



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
        $inputItem = \App\Items::fetchByName($itemName, $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $req = 100;
        if ($itemName == 'Corpse'){
          $req = 1;
        }
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Bio Lab', $contractorID)){
          return [
            'error' => "You need to have a Bio Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Bio Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Bio Material', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'convert-sand-to-silicon'){
        $sand = \App\Items::fetchByName('Sand', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $buildingCaption = "";
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $contractorID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Silicon', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'convert-wood-to-coal'){
        $production = 100;
        if ($robot == null){
          $production = $action->rank * 100;
        }
        $wood = \App\Items::fetchByName('Wood', $contractorID);
        $coal = \App\Items::fetchByName('Coal', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $contractorID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Coal', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;




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
        $itemInput = \App\Items::fetchByName($possibleInputs[$actionName], $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $contractorID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Carbon Nanotubes', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'convert-uranium-ore-to-plutonium'){
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        $buildingCaption = "";
        if (!\App\Buildings::didTheyAlreadyBuildThis('Centrifuge', $contractorID)){
          return [
            'error' => "You need to have a Centrifuge to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Centrifuge', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Plutonium', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'cook-meat' || $actionName == 'cook-flour'){
        if (!\App\Buildings::didTheyAlreadyBuildThis('Campfire', $contractorID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Kitchen', $contractorID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $contractorID)
        ){
          return ['error' => "You don't have the necessary building."];
        }
        $buildingName = 'Campfire';
        $modifier = 1;
        $foodSource = \App\Items::fetchByName(ucfirst(explode('-', $actionName)[1]), $contractorID);
        $wood = \App\Items::fetchByName('Wood', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        if (\App\Buildings::didTheyAlreadyBuildThis('Food Factory', $contractorID)
          && $electricity->quantity >= 100 && $foodSource->quantity >= 100){
          $buildingName='Food Factory';
          $modifier = 100;
        } else if ($foodSource->quantity >= 10 && $wood->quantity >= 5
          && \App\Buildings::didTheyAlreadyBuildThis('Kitchen', $contractorID)){
          $buildingName='Kitchen';
          $modifier = 10;
        } else if (!\App\Buildings::didTheyAlreadyBuildThis('Campfire', $contractorID)){
          $buildingName='Kitchen';
          $modifier = 10;
        }
        $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
        $foodCooked = 2 * $modifier;
        if ($robot == null){
          $foodCooked = $action->rank * 2 * $modifier;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName.$buildingName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Food', $foodCooked, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'explore'){
        $equipmentCaption = '';
        $equipmentAvailable = \App\Equipment
          ::whichOfTheseCanTheyUse(['Car (gasoline)', 'Car (diesel)'], $agentID);
        $land = \App\Land::all();
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $satellite = \App\Items::fetchByName('Satellite', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $satStatus = "";
        $minChance = 1;
        if ($satellite->quantity > 0 && $electricity->quantity >= 100){
          $minChance = 100;
          $electricity->quantity -= 100;
          $electricity->save();
          $satStatus = "<span class='fn'>-100</span> Electricity (Satellite) ["
          . number_format($electricity->quantity) . "] " ;
          if (rand(1, 1000) == 1){
            $satellite->quantity--;
            $satellite->save();
            $satStatus .= " <span class='fn'>-1</span> Satellite";
          }
        } else if (count($equipmentAvailable) > 0){
          $minChance = 10;
          if ($robot == null){
            $equipmentCaption = \App\Equipment::useEquipped($equipmentAvailable[0], $agentID);
            if (!$equipmentCaption){
              return ['error' => "Something technical went wrong with your car. Sorry."];
            }
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
        }
        $status .= "[&empty;] (" . $minChance . ":" . count($land) . ")";
        $landFound = " [";
        if (rand(1, count($land)+1) <= $minChance){
          for ($i=0; $i < $production; $i++){
            $landFound .=  "+" . \App\Land::new($contractorID) . " ";
          }
        }
        $landFound .= "] " . $minChance . ":" . count($land) . ")";
        if ($satStatus != "" || $equipmentCaption != ""){
          $status = "<span class='actionInput'>" . $satStatus . $equipmentCaption
            . "</span> &rarr; " . $landFound;
        }




      } else if ($actionName == 'gather-stone'){
        $status = \App\Items::make('Stone', 1, $contractorID, $agentID);



      } else if ($actionName == 'gather-wood'){
        $status = \App\Items::make('Wood', 10, $contractorID, $agentID);



      } else if ($actionName == 'generate-electricity-with-coal'){
        $buildingCaption = "";
        if (!\App\Buildings::doTheyHaveAccessTo('Coal Power Plant', $agentID)){
          return ['error' => 'You do not have a Coal Power Plant. Build one first please. '];
        }
        $buildingCaption = \App\Buildings::use('Coal Power Plant', $contractorID);
        $production = 1000;
        if ($robot == null){
          $production = $action->rank * 1000;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Electricity', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'generate-electricity-with-plutonium'){
        if ($robot != null){
          return;
        }
        $buildingCaption = "";
        if (!\App\Buildings::doTheyHaveAccessTo('Nuclear Power Plant', $agentID)){
          return ['error' => 'You do not have a Nuclear Power Plant. Build one first please. '];
        }
        $buildingCaption = \App\Buildings::use('Nuclear Power Plant', $contractorID);
        $electricityProduced = $action->rank * 1000000;
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Electricity', $electricityProduced, $contractorID, $agentID);
        $waste = \App\Items::make('Nuclear Waste', 1000, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output . " " . $waste;



      } else if ($actionName == 'harvest-rubber'){
        $equipmentCaption = '';
        $howManyFields = 1;
        $totalRubberYield = 0;
        $equipmentAvailable = \App\Equipment
          ::whichOfTheseCanTheyUse(['Tractor (gasoline)', 'Tractor (diesel)'], $agentID);
        if (!\App\Buildings::doTheyHaveAccessTo('Rubber Plantation', $contractorID)
        || !\App\Buildings::canTheyHarvest('Rubber Plantation', $contractorID)){
          return ['error' => "You either do not have a Rubber Plantation or cannot harvest one right now. Sorry."];
        }
        if (count($equipmentAvailable) > 0){
          $howManyFields = \App\Buildings::howManyFields('Rubber Plantation', $contractorID);
          if ($howManyFields > 10){
            $howManyFields = 10;
          }
          if ($robot == null){
            $equipmentCaption = \App\Equipment::useEquipped($equipmentAvailable[0], $agentID);
            if (!$equipmentCaption){
              return ['error' => "Something technical happened with your equipment not working. Sorry."];
            }
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
        $status = "<span class='fp'>+" . $rubberYield . "</span> Rubber";
        if ($equipmentCaption != ""){
          $status = "<span class='actionInput'>" . $equipmentCaption
            . "</span> &rarr; <span class='fp'>+" . $rubberYield . "</span> Rubber";
        }



      } else if ($actionName == 'harvest-wheat'
        || $actionName == 'harvest-plant-x'
        || $actionName == 'harvest-herbal-greens'){
        $equipmentAvailable = \App\Equipment
          ::whichOfTheseCanTheyUse(['Tractor (gasoline)', 'Tractor (diesel)'], $agentID);
        $equipmentCaption = '';
        $howManyFields = 1;
        $totalYield = 0;
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
        if (!\App\Buildings::doTheyHaveAccessTo($whichItemType[$actionName] . ' Field', $contractorID)
        || !\App\Buildings::canTheyHarvest($whichItemType[$actionName] . ' Field', $contractorID)){
          return ['error' => "You either do not have a " . $whichItemType[$actionName] . " Field or cannot harvest one right now. Sorry."];
        }
        if (count($equipmentAvailable) > 0 ){
          $howManyFields = \App\Buildings::howManyFields($whichItemType[$actionName] . ' Field', $contractorID);
          if ($howManyFields > 10){
            $howManyFields = 10;
          }
          if ($robot == null){
            $equipmentCaption = \App\Equipment::useEquipped($equipmentAvailable[0], $agentID);
            if (!$equipmentCaption){
              return ['error' => "Something technical happened with your equipment not working. Sorry."];
            }
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
          $status = "<span class='actionInput'>" . $whichItemType[$actionName]
            . " Field: <span class='fn'>-" . $howManyFields . "</span> "
            . $equipmentCaption
            . "</span> &rarr; " . $whichItemType[$actionName]
            . ": <span class='fp'>+" . $totalYield . "</span>";



      } else if ($actionName == 'hunt'){
        $meatHunted = 2;
        if ($robot == null){
          $meatHunted = $action->rank * 2;
        }
        $status = \App\Items::make('Meat', $meatHunted, $contractorID, $agentID);



      } else if ($actionName == 'make-BioMeds'){
        if ($robot != null){
          return;
        }
        $production = $action->rank;
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $herbMeds = \App\Items::fetchByName('HerbMeds', $contractorID);
        $bioMaterial = \App\Items::fetchByName('Bio Material', $contractorID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Bio Lab', $contractorID)){
          return [
            'error' => "You need to have a Bio Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Bio Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('BioMeds', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-book'){
        if ($robot != null){
          return;
        }
        $labor = \App\Labor::where('userID', $contractorID)->first();
        if ($labor->availableSkillPoints < 1){
          return [
            'error' => $agentCaption . " do not have enough available skill points."
          ];
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $labor->availableSkillPoints--;
        $labor->save();
        $output = \App\Items::make('Books', $action->rank, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . " <span class='fn'>-1</span> Skill Point "
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-clone'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Clone Vat', $contractorID)){
          return [
            'error' => "You need to have a Clone Vat to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Clone Vat', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Clones', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;




      } else if ($actionName == 'make-contract'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Contracts', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-CPU'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('CPU Fabrication Plant', $contractorID)){
          return [
            'error' => "You need to have a CPU Fabrication Plant to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('CPU Fabrication Plant', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('CPU', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-diesel-bulldozer' || $actionName == 'make-gasoline-bulldozer'
        || $actionName == 'make-diesel-car' || $actionName == 'make-gasoline-car'
        || $actionName == 'make-diesel-tractor' || $actionName == 'make-gasoline-tractor'){
        if (!\App\Buildings::didTheyAlreadyBuildThis('Garage', $contractorID)){
          return [
            'error' => $agentCaption . " do not have a Garage built."
          ];
        }
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $buildingCaption = \App\Buildings::use('Garage', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make(ucfirst(explode('-', $actionName)[2])
          . " (" . explode('-', $actionName)[1] . ")", $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-diesel-engine' || $actionName == 'make-gasoline-engine'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $steel = \App\Items::fetchByName('Steel Ingots', $contractorID);
        $iron = \App\Items::fetchByName('Iron Ingots', $contractorID);
        $copper = \App\Items::fetchByName('Copper Ingots', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $engines = ['make-diesel-engine' => 'Diesel Engines', 'make-gasoline-engine'=>'Gasoline Engines'];
        if ($steel->quantity < 40 || $iron->quantity < 40
          || $copper->quantity < 20 || $electricity->quantity < 100){
          return [
            'error' => $agentCaption . " do not have enough materials to create this engine."
          ];
        } else if (!\App\Buildings::doTheyHaveAccessTo('Machine Shop', $agentID)){
          return [
            'error' => $agentCaption . " do not have a Machine Shop."
          ];
        }
        $buildingCaption = \App\Buildings::use('Machine Shop', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make($engines[$actionName], $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-electric-motor' || $actionName == 'make-gas-motor'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $steel = \App\Items::fetchByName('Steel Ingots', $contractorID);
        $iron = \App\Items::fetchByName('Iron Ingots', $contractorID);
        $copper = \App\Items::fetchByName('Copper Ingots', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $engines = ['make-electric-motor' => 'Electric Motors', 'make-gas-motor'=>'Gas Motors'];
        if ($steel->quantity < 10 || $iron->quantity < 10
          || $copper->quantity < 5 || $electricity->quantity < 25){
          return [
            'error' => $agentCaption . " do not have enough materials to create this motor."
          ];
        } else if (!\App\Buildings::doTheyHaveAccessTo('Machine Shop', $agentID)){
          return [
            'error' => $agentCaption . " do not have a Machine Shop."
          ];
        }
        $buildingCaption = \App\Buildings::use('Machine Shop', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make($engines[$actionName], $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-electric-jackhammer'
      || $actionName == 'make-gas-jackhammer'
      || $actionName == 'make-electric-chainsaw'
      || $actionName == 'make-gas-chainsaw'){
      $availableTools = [
        'make-electric-jackhammer'=>'Jackhammer (electric)',
        'make-gas-jackhammer'     =>'Jackhammer (gasoline)',
        'make-electric-chainsaw'  =>'Chainsaw (electric)',
        'make-gas-chainsaw'       =>'Chainsaw (gasoline)',
      ];
      $production = 1;
      if ($robot == null){
        $production = $action->rank;
      }
      $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
      if (isset($itemCaption['error'])){
        return [
          'error' => $itemCaption['error'],
        ];
      }
      $output = \App\Items::make($availableTools[$actionName], $production,
        $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $itemCaption['status']
        . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-HerbMed'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('HerbMeds', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-iron-axe' || $actionName == "make-iron-handmill"
        || $actionName == 'make-iron-saw' || $actionName == 'make-iron-pickaxe'
        || $actionName == 'make-stone-axe' || $actionName == "make-stone-handmill"
        || $actionName == 'make-stone-saw' || $actionName == 'make-stone-pickaxe'
        || $actionName == 'make-steel-axe' || $actionName == "make-steel-handmill"
        || $actionName == 'make-steel-pickaxe' || $actionName == 'make-steel-saw'
        || $actionName == 'make-steel-shovel' || $actionName == 'make-iron-shovel'
        || $actionName == 'make-stone-shovel'
      ){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make(ucfirst(explode('-', $actionName)[2]) . " ("
          . explode('-', $actionName)[1] . ")", $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-nanites'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Nano Lab', $contractorID)){
          return [
            'error' => "You need to have a Nano Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Nano Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Nanites', $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;





      } else if ($actionName == 'make-NanoMeds'){
        if ($robot != null){
          return;
        }
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Nano Lab', $contractorID)){
          return [
            'error' => "You need to have a Nano Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Nano Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('NanoMeds', $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;





      } else if ($actionName == 'make-robot'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Robotics Lab', $contractorID)){
          return [
            'error' => "You need to have a Robotics Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Robotics Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Robots', $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;




    } else if ($actionName == 'make-rocket-engine'){
      $production = 1;
      if ($robot == null){
        $production = $action->rank;
      }
      if (!\App\Buildings::didTheyAlreadyBuildThis('Propulsion Lab', $contractorID)){
        return [
          'error' => "You need to have a Propulsion Lab to do this.",
        ];
      }
      $buildingCaption = \App\Buildings::use('Propulsion Lab', $contractorID);
      $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
      if (isset($itemCaption['error'])){
        return [
          'error' => $itemCaption['error'],
        ];
      }
      $output = \App\Items::make('Robots', $production,
        $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
        . "</span> &rarr; " . $output;





      } else if ($actionName == 'make-tire' || $actionName == 'make-radiation-suit'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $itemName = 'Tires';
        $buildingCaption = "";
        if ($actionName == 'make-radiation-suit'){
          $itemName = 'Radiation Suit';
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Chem Lab', $contractorID)){
          return [
            'error' => "You need to have a Chem Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Chem Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make($itemName, $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-paper'){
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Paper', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . "</span> &rarr; " . $output;




      } else if ($actionName == 'make-satellite'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        if (!\App\Buildings::didTheyAlreadyBuildThis('Propulsion Lab', $contractorID)){
          return [
            'error' => "You need to have a Propulsion Lab to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Propulsion Lab', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Satellite', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'make-solar-panel'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $steel = \App\Items::fetchByName('Steel Ingots', $contractorID);
        $copper = \App\Items::fetchByName('Copper Ingots', $contractorID);
        $silicon = \App\Items::fetchByName('Silicon', $contractorID);
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $buildingCaption = "";

        if (!\App\Buildings::didTheyAlreadyBuildThis('Solar Panel Fabrication Plant', $contractorID)){
          return [
            'error' => "You need to have a Solar Panel Fabrication Plant to do this.",
          ];
        }
        $buildingCaption = \App\Buildings::use('Solar Panel Fabrication Plant', $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Solar Panels', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status'] . $buildingCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'mill-flour'){
        $wheat = Items::fetchByName('Wheat', $contractorID);
        if ((($robot == null && !Labor::areTheyEquippedWith('Handmill', $agentID))
          || ($robot != null && !\App\Robot::areTheyEquippedWith('Handmill', $robotID)))
          && !\App\Buildings::didTheyAlreadyBuildThis('Gristmill', $contractorID)){
          return ['error' => "You do not have a Handmill or Gristmill"];
        } else if ((($robot == null && !Labor::areTheyEquippedWith('Handmill', $agentID))
          || ($robot != null && !\App\Robot::areTheyEquippedWith('Handmill', $robotID)))
          && \App\Buildings::didTheyAlreadyBuildThis('Gristmill', $contractorID)
          && $wheat->quantity < 100){
          return ['error' => "You have a Gristmill built but don't enough Wheat."];
        }
        $buildingCaption = '';
        $equipmentCaption = '';
        $buildingName = '';
        if (\App\Buildings::didTheyAlreadyBuildThis('Gristmill', $contractorID) && $wheat->quantity >= 100){
          $modifier = 100;
          $buildingName = 'Gristmill';
          $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
        } else {
          if ($robot == null){
            $equipmentCaption = Equipment::useEquipped('Handmill', $agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
          $modifier = 10;
        }
        $production = $modifier * .5;
        if ($robot == null){
          $production = $action->rank * ($modifier * .5);
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName . $buildingName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Flour', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . $buildingCaption . $equipmentCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'mill-log'){
        $logs = Items::fetchByName('Logs', $contractorID);
        if (( ($robot == null && !Labor::areTheyEquippedWith('Saw', $agentID))
          || ($robot != null && !\App\Robot::areTheyEquippedWith('Saw', $robotID)))
          && !\App\Buildings::didTheyAlreadyBuildThis('Sawmill', $contractorID)){
          return ['error' => 'You either do not have a Saw equipped or do not have a Sawmill.'];
        } else if ((($robot == null && !Labor::areTheyEquippedWith('Saw', $agentID))
            || ($robot != null && !\App\Robot::areTheyEquippedWith('Saw', $robotID)))
            && \App\Buildings::didTheyAlreadyBuildThis('Sawmill', $contractorID)
            && $logs->quantity < 10){
            return ['error' => "You have a Sawmill built but don't enough Logs (10)."];
        }
        $buildingCaption = '';
        $equipmentCaption = '';
        $buildingName = '';
        if (\App\Buildings::didTheyAlreadyBuildThis('Sawmill', $contractorID) && $logs->quantity >= 10){
          $modifier = 10;
          $buildingName = 'Sawmill';
          $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
        } else {
          if ($robot == null){
            $equipmentCaption = Equipment::useEquipped('Saw', $agentID);
          } else {
            $equipmentCaption = \App\Robot::useEquipped($robotID);
          }
          $modifier = 1;
        }
        $production = 100 * $modifier;
        if ($robot == null){
          $production = $action->rank * 100 * $modifier;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName . $buildingName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Wood', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . $buildingCaption . $equipmentCaption
          . "</span> &rarr; " . $output;



      } else if ($actionName == 'mine-sand'){
        $equipmentCaption = "";
        $leaseStatus = '';
        $landBonus = \App\Land::count('desert', $agentID);
        $equipmentAvailable = \App\Equipment
          ::whichOfTheseCanTheyUse(['Bulldozer (gasoline)', 'Bulldozer (diesel)',
          'Shovel'], $agentID);
        if (count($equipmentAvailable) < 1){
          return ['error' => "You don't have any equipment to mine Sand."];
        }
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
        $buildingCaption = "";
        $modifier = 10;
        if (\App\Buildings::didTheyAlreadyBuildThis('Mine', $contractorID)){
          $buildingCaption = \App\Buildings::use('Mine', $contractorID);
          $modifier = 100;
        }
        if (($robot == null && $equipmentAvailable[0] != 'Shovel')
          || ($robot != null
          && !\App\Robot::areTheyEquippedWith('Shovel', $robotID))){
          $modifier *= 10;
        }
        $production = $modifier;
        if ($robot == null){
          $equipmentCaption = Equipment::useEquipped($equipmentAvailable[0], $agentID);
          $production = $action->rank * ($modifier + $landBonus);

        } else {
          $equipmentCaption = \App\Robot::useEquipped($robotID);
        }
        $landResource = \App\Land::takeResource('Sand',  $agentID, $production, true);
        if ($landResource != true){
          return $landResource;
        }
        $output = \App\Items::make('Sand', $production, $contractorID, $agentID);
        $status = "<span class='actionInput'>";
        if ($agentID == $contractorID){
          $status .= $equipmentCaption . $leaseStatus;
        }
        $status .= $buildingCaption . "</span> &rarr; " . $output;




      } else if ($actionName == 'mine-coal' || $actionName == 'mine-iron-ore'
        || $actionName == 'mine-stone' || $actionName == 'mine-copper-ore'
        || $actionName == 'mine-uranium-ore'){
        $equipmentAvailable = \App\Equipment
          ::whichOfTheseCanTheyUse(['Jackhammer (gasoline)', 'Jackhammer (electric)',
          'Pickaxe'], $agentID);
        if (count($equipmentAvailable) < 1){
          return ['error' => "You don't have any equipment to mine with right now."];
        }
        $labor = \App\Labor::where('userID', $agentID)->first();
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
        if ($labor->alsoEquipped != null){
          $equipment = \App\Equipment::find($labor->alsoEquipped);
          $itemType = \App\ItemTypes::find($equipment->itemTypeID);
          if ($itemType->name != 'Radiation Suit'
            && $actionName ==  'mine-uranium-ore'){
            return [
              'error' => $agentCaption . " need a Radiation Suit equipped in order to mine Uranium Ore."
            ];
          }
        }
        $buildingCaption = "";
        $modifier = 10;
        if (\App\Buildings::didTheyAlreadyBuildThis('Mine', $contractorID)){
          $buildingCaption = \App\Buildings::use('Mine', $contractorID);
          $modifier = 100;
        }
        if (($robot == null && $equipmentAvailable[0] != 'Pickaxe')
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
          $equipmentCaption = Equipment::useEquipped($equipmentAvailable[0], $agentID);
          if (!$equipmentCaption){
            return ['error' => 'Something technical happened with your equipment. Sorry'];
          }
          $production = $action->rank * ($modifier + $landBonus);

        } else {
          $equipmentCaption = \App\Robot::useEquipped($robotID);
        }
        $landResource = \App\Land::takeResource($miningArr[$actionName]['item'],  $agentID, $production, true);
        if ($landResource != true){
          return $landResource;
        }
        if ($actionName ==  'mine-uranium-ore' && $robot == null
          && $labor->alsoEquipped != null){
            $equipment->uses--;
            $equipment->save();
            if ($equipment->uses < 1){
              \App\Equipment::destroy($equipment->id);
              $labor = \App\Labor::where('userID', $agentID)->first();
              $labor->alsoEquipped = null;
              $labor->save();
            }
            $equipmentCaption .= " Radiation Suit: <span class='fn'>"
              . number_format($equipment->uses / $equipment->totalUses * 100, 2 )
              . "%</span> ";
        }
        $output = \App\Items::make($miningArr[$actionName]['item'], $production, $contractorID, $agentID);
        if ($buildingCaption != ""){
          $status .=  "<span class='actionInput'>";
        }
        if ($agentID == $contractorID){
          $status .= $equipmentCaption  . $leaseStatus;
        }
        if ($buildingCaption != ""){
          $status .= " " . $buildingCaption . "</span> &rarr; ";
        }
        $status .= $output;



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
        $status = "<span class='actionInput'>";
        if ($contractorID == $agentID){
          $status .= $leaseStatus;
        }
        $status .= "Building Slots: <span class='fn'>-1</span> ["
          . number_format($contractor->buildingSlots)
          . "]</span> &rarr; Rubber Plantation: <span class='fp'>+1</span>";



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
        $status = "<span class='actionInput'>Building Slots: <span class='fn'>-1</span> ["
          . number_format($contractor->buildingSlots) . "]</span> &rarr; "
          . $whichItemType[$actionName] . " Field: <span class='fp'>+1</span>";



      } else if ($actionName == 'pump-oil'){
        if (!\App\Buildings::didTheyAlreadyBuildThis('Oil Well', $agentID)){
          return ['error' => "You do not have an Oil Well. Please build one first."];
        }
        $buildingCaption = \App\Buildings::use('Oil Well', $contractorID);
        $production = 10;
        if ($robot == null){
          $production = $action->rank * 10;
        }
        $landResource = \App\Land::takeResource('Oil',  $agentID, $production, true);
        if ($landResource != true){
          return $landResource;
        }
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make('Oil', $production, $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . $buildingCaption . "</span> &rarr; " . $output;



      } else if ($actionName == 'refine-oil'){
        $production = 1;
        if ($robot == null){
          $production = $action->rank;
        }
        $electricity = \App\Items::fetchByName('Electricity', $contractorID);
        $oil = \App\Items::fetchByName ('Oil', $contractorID);

        if (!\App\Buildings::didTheyAlreadyBuildThis('Oil Refinery', $contractorID)){
          return ['error' => "You do not have an Oil Refinery. Please build one first."];
        }
        $buildingCaption = \App\Buildings::use('Oil Refinery', $contractorID);
        $output = \App\Items::make('Jet Fuel', 1 * $production, $contractorID, $agentID);
        $output .= \App\Items::make('Gasoline', 5 * $production, $contractorID, $agentID);
        $output .= \App\Items::make('Diesel Fuel', 4 * $production, $contractorID, $agentID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . $buildingCaption . "</span> &rarr; " . $output;



      } else if ($actionName == 'smelt-copper' || $actionName == 'smelt-iron'
        || $actionName == 'smelt-steel'){
        $itemInputArr = ['smelt-copper' => 'Copper Ore',
          'smelt-iron' => 'Iron Ore', 'smelt-steel' => 'Iron Ingots'];
        $itemOutputArr = ['smelt-copper' => 'Copper Ingots',
          'smelt-iron' => 'Iron Ingots', 'smelt-steel' => 'Steel Ingots'];
        $coal = Items::fetchByName('Coal', $contractorID);
        $electricity = Items::fetchByName('Electricity', $contractorID);
        $input = Items::fetchByName($itemInputArr[$actionName], $contractorID);
        if (!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $contractorID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $contractorID)
          && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $contractorID)){
          return ['error' => "You don't have a Small Furnace, Large Furnace or Electric Arc Furnace."];
        } else if ($input->quantity < 10){
          return ['error' => "You don't have enough Copper Ore."];
        } else if(!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $contractorID)
        && !\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $contractorID)
        && \App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $contractorID)
        && ($electricity->quantity < 1000 || $input->quantity < 1000)){
          return ['error' => "You have an Electric Arc Furnace but don't have enough Electricity or Copper Ore."];
        } else if ((!\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $contractorID)
        || (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $contractorID)
        && ($input->quantity < 1000 || $electricity->quantity < 1000)))
        && (\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $contractorID)
        || \App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $contractorID)) && $coal->quantity < 10){
          return ['error' => "You have a Small Furnace  or Large Furnace but don't have enough Coal."];
        }
        $buildingCaption = '';

        $copperIngots = Items::fetchByName('Copper Ingots', $contractorID);
        $buildingName = 'Small Furnace';
        $modifier = 10;
        $productionModifier = 1;
        if (\App\Buildings::didTheyAlreadyBuildThis('Electric Arc Furnace', $contractorID)
          && $electricity->quantity >= 1000 && $input->quantity >= 1000){
            $modifier = 1000;
            $productionModifier = 100;
            $buildingName = 'Electric Arc Furnace';
        } else if (\App\Buildings::didTheyAlreadyBuildThis('Large Furnace', $contractorID)){
          if ($input->quantity >= 100 && $coal->quantity >= 100){
            $modifier = 100;
            $productionModifier = 10;
            $buildingName = 'Large Furnace';
          }
          if (!\App\Buildings::didTheyAlreadyBuildThis('Small Furnace', $contractorID)){
            $buildingName = 'Large Furnace';
          }
        }
        $production = $productionModifier;
        if ($robot == null){
          $production = $action->rank  * $productionModifier;
        }
        $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
        $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName . $buildingName), $contractorID);
        if (isset($itemCaption['error'])){
          return [
            'error' => $itemCaption['error'],
          ];
        }
        $output = \App\Items::make($itemOutputArr[$actionName], $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $itemCaption['status']
          . $buildingCaption
          . "</span> &rarr; " . $output;



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
        $output = \App\Items::make('Electricity', $production,
          $contractorID, $agentID);
        $status =  "<span class='actionInput'>" . $buildingCaption
          . "</span> &rarr; " . $output;



      }
      if ($robot == null){
        $user = \App\User::find($agentID);
        $user->lastAction = date("Y-m-d H:i:s");
        $user->save();
      }
      $childrenStatus = \App\Labor::feedChildren($agentID);
      return ['status' => $status . " " . $childrenStatus];
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
          && !\App\Buildings::doTheyHaveAccessTo($buildingType->name, Auth::id())){
          $availableBuildings[] = $buildingType->name;
        }
      }
      return $availableBuildings;
    }

    public static function fetchRobotActions(){
      $bannedActions = \App\Robot::fetchBannedActions();
      $robots = \App\Robot::fetch();
      $robotActions = [];
      foreach ($robots as $robot){
        $actionType = \App\ActionTypes::find($robot->actionTypeID);
        foreach ($robots as $robot){
          if (!in_array($actionType->name, $bannedActions)){
            $robotActions[$actionType->id] = $actionType->name;
          }
        }

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

    public static function reset($legacy){
      $actions = \App\Actions::where('userID', \Auth::id())->get();
      foreach($actions as $action){
        if (!$legacy){
          $action->totalUses = 0;
          $action->nextRank = 150;
          $action->rank = 1;
        }
        $action->unlocked = false;
        $action->save();
      }
    }
}
