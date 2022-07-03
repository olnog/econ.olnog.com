<?php
namespace App;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use \App\Skills;
use \App\Labor;

class Actions extends Model
{
  protected $table = 'actions';

  public static function do($actionName, $agentID, $contractorID, $robotID, $useFood, $offline){
    //could possible streamline if statements like I did fetchActionable
    $action = \App\Actions::fetchByName($agentID, $actionName);
    $electricityCaption = "";
    $foodCaption = "";
    $feedingChildren = \App\Labor::feedChildren($agentID);
    $childrenStatus = '';
    $foodUsed = 0;
    if ($feedingChildren !== null){
      $childrenStatus = " (Children) ";
      $foodUsed += $feedingChildren;
      if ($feedingChildren === false ){
        $childrenStatus = " Children: 0 ";
      }
    }
    if ($useFood != null || $feedingChildren !== null){


      if ($useFood == null){

        $food = \App\Items::fetchByName('Food', $agentID);
      } else {
        $foodUsed = 0;
        $food = \App\Items::fetchByName('Food', $useFood);
      }
      if ($food == null){
        \App\History::new(5, 'bugs', $useFood . " does not have food.  BUG");
      }
      if ($food != null && $useFood != null){
        if ($food->quantity == 0){
          return ['error' => "You're automating actions but you don't have any more food." ];
        }

        $food->quantity--;
        $food->save();
        $foodUsed = 1;
      }
      if ($food != null){
        $foodCaption = $childrenStatus . "Food: <span class='fn'>-" . $foodUsed
          . "</span> [" . number_format($food->quantity) . "] ";
      }
    }
    if ($robotID != null){
      $electricity = \App\Items::fetchByName('Electricity', $agentID);
      if ($electricity->quantity < 100){
        return [
          'error'
            => "You don't have enough Electricity to operate this Robot."
          ];

      }
      $electricity->quantity -= 100;
      $electricity->save();
      $electricityCaption = "Electricity: <span class='fn'>-100 ["
        . number_format($electricity->quantity) . "] ";
    }
    $buildingCaption = "";
    $equipmentCaption = "";
    $production = \App\Actions::fetchBaseProduction($actionName, $robotID,
      $agentID);
    $reqBuildings = \App\Buildings
      ::whichBuildingsDoTheyHaveAccessTo($actionName, $contractorID);

    $robot = null;
    $status = "";
    if ($reqBuildings !== null && count($reqBuildings) < 1){
      $agentCaption = "You ";
      if ($agentID != $contractorID){
        $agentCaption = "You (if paying a freelancer) or they (if being hired) ";
      }
      return ['error' => $agentCaption . " need to build or repair the following building ("
        .  implode(', ', \App\Buildings::fetchRequiredBuildingsFor($actionName))
        . ") in order to do this."];
    } else if (!\App\Items::doTheyHaveEnoughFor($actionName)){
      return ['error' => \App\Items::use(\App\Items
        ::fetchActionItemInput($actionName), $contractorID)['error']];
    } else if ($robotID == null && (!$action->unlocked || $action->rank == 0)){
      return ['error' => "This action hasn't been unlocked yet.",];
    }

    if ($robotID != null){
      $robot = \App\Robot::find($robotID);
    }
    if ($useFood == null && $robot == null && $agentID == $contractorID
      && strtotime('now') - strtotime(\App\User::find($agentID)->lastAction)
        == 0){
      return ['error' => "Sorry, you're doing this too often."];
    }

    $equipmentAvailable = \App\Equipment
      ::whichOfTheseCanTheyUse(\App\Equipment::whichEquipment($actionName), $agentID);

    \App\Metric::newAction($agentID, $actionName);

    if ($actionName == 'chop-tree'){
      $leaseStatus = '';
      $landBonus = \App\Land::where('type', 'forest')
        ->where('userID', $contractorID)->count();
      if (count($equipmentAvailable) == 0){
        return [
          'error' =>
            "You do not have any equipment that can be used to chop down a tree."
        ];
      } else if (!\App\Land::doTheyHaveAccessTo('forest', $contractorID)){
        return ['error' => "You don't have access to any Forests. Buy or lease some Forest."];
      }
      if (!\App\Land::doTheyOwn('forest', $contractorID)){
        $landBonus = 1;
        $leaseStatus = \App\Lease::use('forest', $contractorID);
      }
      $baseChop = 1;
      if ($robot == null && ($equipmentAvailable[0] == 'Chainsaw (gasoline)'
        || $equipmentAvailable[0] == 'Chainsaw (electric)')){
        $baseChop = 10;
      }
      $production = $baseChop;
      if ($robot == null){
        $equipmentCaption = Equipment
          ::useEquipped($equipmentAvailable[0], $agentID);
        if (!$equipmentCaption){
          return [
            'error' => "Something went wrong with an equipment check. Sorry."
          ];
        }
        $production = $action->rank * $baseChop * $landBonus;
      }
      $landResource = \App\Land::takeResource('Logs',  $agentID,
        $production, true);
      if ($landResource == false){
        return $landResource;
      }
      $output = \App\Items::make('Logs', $production, $contractorID,
        $agentID);
      if ($agentID == $contractorID){
        $status = "<span class='actionInput'>" . $electricityCaption . $foodCaption . $equipmentCaption
          . $leaseStatus . "</span> &rarr; ";
      }
      $status .= $output;


    } else if ($actionName == 'cook-meat' || $actionName == 'cook-flour'){
      $buildingName = 'Campfire';
      $modifier = 1;
      $foodSource = \App\Items::fetchByName(ucfirst(explode('-', $actionName)[1]), $contractorID);
      $wood = \App\Items::fetchByName('Wood', $contractorID);
      $electricity = \App\Items::fetchByName('Electricity', $contractorID);
      if (\App\Buildings::doTheyHaveAccessTo('Food Factory', $contractorID)
        && $electricity->quantity >= 100 && $foodSource->quantity >= 100){
        $buildingName='Food Factory';
        $modifier = 100;
      } else if ($foodSource->quantity >= 10 && $wood->quantity >= 5
        && \App\Buildings::doTheyHaveAccessTo('Kitchen', $contractorID)){
        $buildingName='Kitchen';
        $modifier = 10;
      } else if (!\App\Buildings::doTheyHaveAccessTo('Campfire', $contractorID)){
        $buildingName='Kitchen';
        $modifier = 10;
      }
      $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
      if (isset($buildingCaption['error'])){
        return $buildingCaption;
      }
      $foodCooked = 2 * $modifier;
      if ($robot == null){
        $foodCooked = $action->rank * 2 * $modifier;
      }
      $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName.$buildingName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $output = \App\Items::make('Food', $foodCooked, $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status'] . $buildingCaption
        . "</span> &rarr; " . $output;


    } else if ($actionName == 'explore'){
      $numOfParcels = \App\Land::where('userID', '>', 0)->count() + 1;
      $satellite = \App\Items::fetchByName('Satellite', $contractorID);
      $electricity = \App\Items::fetchByName('Electricity', $contractorID);
      $satStatus = "";
      $minChance = 1;
      if ($satellite->quantity > 0 && $electricity->quantity >= 10000){
        $minChance = 10000;
        $electricity->quantity -= 10000;
        $electricity->save();
        $satStatus = " Electricity:<span class='fn'>-10,000</span>  (Satellite) "
        . number_format($electricity->quantity) . "] " ;
        if (rand(1, 10000) == 1){
          $satellite->quantity--;
          $satellite->save();
          $satStatus .= "Satellites: <span class='fn'>-1</span> ";
        }
      } else if (count($equipmentAvailable) > 0){
        $minChance = 100;
        if ($robot == null){
          $equipmentCaption = \App\Equipment::useEquipped($equipmentAvailable[0], $agentID);
          if (!$equipmentCaption){
            return ['error' => "Something technical went wrong with your car. Sorry."];
          }
        }
      }
      if ($foodCaption != "" || $electricityCaption != ""){
        $status .= "<span class='actionInput'>" . $foodCaption . $electricityCaption . "</span> &rarr; ";
      }

      $numOfLandfound = 0;
      $landFound = " [";
      if (rand(1, $numOfParcels) <= $minChance){
        for ($i=0; $i < $production; $i++){
          $landFound .=  "+" . \App\Land::new($contractorID) . " ";
          $numOfLandfound++;
        }
      }
      $landFound .= "] " . $minChance . ":" . $numOfParcels . ")";
      if ($numOfLandfound > 0 ){
        $status .= $landFound;
      } else {
        $status .= "<span class='fn'>No land found!</span> (" . $minChance . ":" . $numOfParcels . " chance)";
      }
      if ($satStatus != "" || $equipmentCaption != ""){
        $status = "<span class='actionInput'>" . $electricityCaption . $foodCaption . $satStatus . $equipmentCaption
          . "</span> &rarr; " . $landFound;
      }



    } else if ($actionName == 'harvest-wheat'
      || $actionName == 'harvest-plant-x'
      || $actionName == 'harvest-herbal-greens'
      || $actionName == 'harvest-rubber'){
      $howManyFields = 1;
      $totalYield = 0;
      $itemName = \App\Items::fetchItemNameForAction($actionName);
      $fieldName = ' Field';
      if ($itemName == 'Rubber'){
        $fieldName = ' Plantation';
      }
      $whichVarName = [
        'harvest-wheat' => 'wheat',
        'harvest-plant-x' => 'plantX',
        'harvest-herbal-greens'=> 'herbalGreens',
        'harvest-rubber'=> 'rubber',
      ];
      if (!\App\Buildings
        ::canTheyHarvest($itemName . $fieldName, $contractorID)){
        return ['error' => "You either do not have a " . $itemName . $fieldName
          . " or cannot harvest one right now. Sorry."];
      }
      if (count($equipmentAvailable) > 0 ){
        $howManyFields = \App\Buildings::howManyFields($itemName . $fieldName,
          $contractorID);
        if ($howManyFields > 10){
          $howManyFields = 10;
        }
        if ($robot == null){
          $equipmentCaption = \App\Equipment
            ::useEquipped($equipmentAvailable[0], $agentID);
          if (!$equipmentCaption){
            return [
              'error'
                => "Something technical happened with your equipment not working. Sorry."
            ];
          }
        }
      }
      for ($i = 0; $i < $howManyFields; $i++){
        $produce = Items::fetchByName($itemName, $contractorID);
        $field = \App\Buildings::fetchField($itemName . $fieldName,
          $contractorID);
        $yield = $field[$whichVarName[$actionName]];
        if ($robot == null){
          $yield = $field[$whichVarName[$actionName]] * $action->rank;
        }
        \App\Buildings::destroy($field->id);
        $user = \App\User::find($contractorID);

        $produce->quantity += $yield;
        $produce->save();
        $totalYield += $yield;
      }
      $status = "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemName . $fieldName
        . ": <span class='fn'>-" . $howManyFields . "</span> "
        . $equipmentCaption . "</span> &rarr; " . $itemName
        . ": <span class='fp'>+" . $totalYield . "</span>";




    } else if ($actionName == 'make-book'){
      if ($robot != null){
        return [
          'error' => "You can't do this with a robot."
        ];
      }
      $labor = \App\Labor::where('userID', $contractorID)->first();
      if ($labor->availableSkillPoints < 1){
        return [
          'error' => "You do not have enough available skill points."
        ];
      }
      $itemCaption = \App\Items::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $labor->availableSkillPoints--;
      $labor->save();
      $output = \App\Items::make('Books', $action->rank, $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status']
        . " <span class='fn'>-1</span> Skill Point "
        . "</span> &rarr; " . $output;


    } else if ($actionName == 'mill-wheat'){
      $wheat = Items::fetchByName('Wheat', $contractorID);
      if ($robot == null && !Labor::areTheyEquippedWith('Handmill', $agentID)
        && !\App\Buildings::doTheyHaveAccessTo('Gristmill', $contractorID)){
        return ['error' => "You do not have a Handmill or Gristmill"];
      } else if ($robot == null
        && !Labor::areTheyEquippedWith('Handmill', $agentID)
        && \App\Buildings::doTheyHaveAccessTo('Gristmill', $contractorID)
        && $wheat->quantity < 100){
        return ['error' => "You have a Gristmill but not enough Wheat."];
      }
      $buildingName = '';
      $modifier = 10;
      if ($robot == null
        && \App\Buildings::doTheyHaveAccessTo('Gristmill', $contractorID)
        && $wheat->quantity >= 100){
        $modifier = 100;
        $buildingName = 'Gristmill';
        $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
        if (isset($buildingCaption['error'])){
          return $buildingCaption;
        }
      } else if ($robot == null
        && Labor::areTheyEquippedWith('Handmill', $agentID)){
        $equipmentCaption = Equipment::useEquipped('Handmill', $agentID);
      }
      $production = $modifier * .5;
      if ($robot == null){
        $production = $action->rank * ($modifier * .5);
      }
      $itemCaption = \App\Items
        ::use(\App\Items
        ::fetchActionItemInput($actionName . $buildingName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $output = \App\Items::make('Flour', $production, $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status']
        . $buildingCaption . $equipmentCaption
        . "</span> &rarr; " . $output;



    } else if ($actionName == 'mill-log'){
      $logs = Items::fetchByName('Logs', $contractorID);
      if ($robot == null && !Labor::areTheyEquippedWith('Saw', $agentID)
        && !\App\Buildings::doTheyHaveAccessTo('Sawmill', $contractorID)){
        return ['error' => 'You either do not have a Saw equipped or do not have a Sawmill.'];
      } else if ($robot == null && !Labor::areTheyEquippedWith('Saw', $agentID)
          && \App\Buildings::doTheyHaveAccessTo('Sawmill', $contractorID)
          && $logs->quantity < 10){
          return ['error' => "You have a Sawmill built but don't enough Logs (10)."];
      }
      $buildingName = '';
      $modifier = 1;
      if ($robot == null && $logs->quantity >= 10
        && \App\Buildings::doTheyHaveAccessTo('Sawmill', $contractorID)){
        $modifier = 10;
        $buildingName = 'Sawmill';
        $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
        if (isset($buildingCaption['error'])){
          return $buildingCaption;
        }
      } else if ($robot == null && Labor::areTheyEquippedWith('Saw', $agentID)){
          $equipmentCaption = Equipment::useEquipped('Saw', $agentID);
      }
      $production *= $modifier;
      $itemCaption = \App\Items
        ::use(\App\Items
        ::fetchActionItemInput($actionName . $buildingName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $output = \App\Items::make('Wood', $production, $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status']
        . $buildingCaption . $equipmentCaption
        . "</span> &rarr; " . $output;



    } else if ($actionName == 'mine-sand'){
      $leaseStatus = '';
      $landBonus = \App\Land::where('type', 'desert')
        ->where('userID', $contractorID)->count();
      if (count($equipmentAvailable) < 1){
        return ['error' => "You don't have any equipment to mine Sand."];
      } else if (!\App\Land::doTheyHaveAccessTo('desert', $contractorID)){
        return ['error' => "You don't have access to any Desert. Buy or lease some Desert."];
      }
      if (!\App\Land::doTheyOwn('desert', $agentID)){
        $landBonus = 1;
        $leaseStatus = \App\Lease::use('desert', $agentID);
      }
      $modifier = 10;
      if (\App\Buildings::doTheyHaveAccessTo('Mine', $contractorID)){
        $buildingCaption = \App\Buildings::use('Mine', $contractorID);
        if (isset($buildingCaption['error'])){
          return $buildingCaption;
        }
        $modifier = 100;
      }
      if ($robot == null && $equipmentAvailable[0] != 'Shovel'){
        $modifier *= 10;
      }
      $production = $modifier;
      if ($robot == null){
        $equipmentCaption = Equipment::useEquipped($equipmentAvailable[0], $agentID);
        $production = $action->rank * ($modifier + $landBonus);
      }
      $landResource = \App\Land::takeResource('Sand',  $agentID, $production, true);
      if ($landResource != true){
        return $landResource;
      }
      $output = \App\Items::make('Sand', $production, $contractorID, $agentID);
      $status = "<span class='actionInput'>";
      if ($agentID == $contractorID){
        $status .=  $foodCaption . $equipmentCaption . $leaseStatus;
      }
      $status .= $buildingCaption . "</span> &rarr; " . $output;


    } else if ($actionName == 'mine-coal' || $actionName == 'mine-iron-ore'
      || $actionName == 'mine-stone' || $actionName == 'mine-copper-ore'
      || $actionName == 'mine-uranium-ore'){
      $itemName = \App\Items::fetchItemNameForAction($actionName);
      $labor = \App\Labor::where('userID', $contractorID)->first();
      if (count($equipmentAvailable) < 1){
        return ['error' => "You don't have any equipment to mine with right now."];
      } else if (!\App\Land::doTheyHaveAccessTo('mountains', $contractorID)){
        return ['error' => "You don't have access to any Mountains. Buy or lease some Mountains."];
      } else if ($labor->alsoEquipped != null){
        $equipment = \App\Equipment::find($labor->alsoEquipped);
        $itemType = \App\ItemTypes::find($equipment->itemTypeID);
        if ($itemType->name != 'Radiation Suit'
          && $actionName ==  'mine-uranium-ore'){
          return [
            'error' => "You need a Radiation Suit equipped in order to mine Uranium Ore."
          ];
        }
      }
      $leaseStatus = '';
      $landBonus = \App\Land::where('type', 'mountains')
        ->where('userID', $contractorID)->count();
      if (!\App\Land::doTheyOwn('mountains', $agentID)){
        $leaseStatus = \App\Lease::use('mountains', $agentID);
        $landBonus = 1;
      }
      $modifier = 10;
      if (\App\Buildings::doTheyHaveAccessTo('Mine', $contractorID)){
        $buildingCaption = \App\Buildings::use('Mine', $contractorID);
        if (isset($buildingCaption['error'])){
          return $buildingCaption;
        }
        $modifier = 100;
      }
      if ($robot == null && $equipmentAvailable[0] != 'Pickaxe'){
        $modifier *= 10;
      }
      $production = $modifier;
      if ($robot == null){
        $equipmentCaption = Equipment::useEquipped($equipmentAvailable[0], $agentID);
        if (!$equipmentCaption){
          return ['error' => 'Something technical happened with your equipment. Sorry'];
        }
        $production = $action->rank * ($modifier + $landBonus);
      }
      $landResource = \App\Land::takeResource($itemName,  $agentID, $production, true);
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
      $output = \App\Items::make($itemName, $production, $contractorID, $agentID);
      if ($buildingCaption != ""){
        $status .=  "<span class='actionInput'>";
      }
      if ($agentID == $contractorID){
        $status .= $foodCaption . $equipmentCaption  . $leaseStatus;
      }
      if ($buildingCaption != ""){
        $status .= " " . $buildingCaption . "</span> &rarr; ";
      }
      $status .= $output;


    } else if ($actionName == 'plant-rubber-plantation'){
      $leaseStatus = '';
      $landBonus = \App\Land::where('type', 'jungle')
        ->where('userID', $contractorID)->count();
      if (!\App\Land::doTheyOwn('jungle', $contractorID)){
        $currentlyLeasing = \App\Lease::areTheyAlreadyLeasing('jungle', $contractorID);
        if ($currentlyLeasing){
          $landBonus = \App\Lease::howManyAreTheyLeasing('jungle', $contractorID);
          $leaseStatus = \App\Lease::use('jungle', $contractorID);
        }
        if ($leaseStatus == false || !$currentlyLeasing){
          return [
            'error' => "You don't have access to any jungles. Sorry."
          ];
        }
      }
      if (\App\Buildings::howManyFieldsForThisLandType('jungle', $contractorID) >= $landBonus){
        return [
          'error' => "Unfortunately, the number of Rubber Plantations you can have are limited to the number of Jungles you have access to and you're maxed out."
        ];
      }
      $production = 10;
      if ($robot == null){
        $production = $action->rank * (10 + $landBonus);
      }
      $rubberPlantationType = \App\BuildingTypes::fetchByName('Rubber Plantation');
      $contractor = \App\User::find($contractorID);
      if (\App\Buildings::howManyBuildingsAndFieldsDoTheyHave($contractorID)
        >= $contractor->buildingSlots){
        return ['error' => " You don't have enough building slots."];
      }

      $wheatField = new \App\Buildings;
      $wheatField->buildingTypeID = $rubberPlantationType->id;
      $wheatField->userID = $contractorID;
      $wheatField->rubber = $production;
      $wheatField->harvestAfter = date("Y-m-d H:i:s", strtotime('+24 hours'));
      $wheatField->save();
      $status = "<span class='actionInput'>";
      if ($contractorID == $agentID){
        $status .=  $foodCaption . $leaseStatus;
      }
      $status .= "Building Slots: <span class='fn'>-1</span> ["
        . number_format($contractor->buildingSlots)
        . "]</span> &rarr; Rubber Plantation: <span class='fp'>+1</span>";


    } else if ($actionName == 'plant-wheat-field'
      || $actionName == 'plant-plant-x-field'
      || $actionName == 'plant-herbal-greens-field'){
      $contractor = \App\User::find($contractorID);
      if (\App\Buildings::howManyBuildingsAndFieldsDoTheyHave($contractorID)
        >= $contractor->buildingSlots){
        return ['error' => " You don't have enough building slots."];
      }
      $itemName = \App\Items::fetchItemNameForAction($actionName);
      $whichVarName = [
        'plant-wheat-field' => 'wheat',
        'plant-plant-x-field' => 'plantX',
        'plant-herbal-greens-field'=> 'herbalGreens'
      ];
      $landBonus = \App\Land::where('type', 'plains')
        ->where('userID', $contractorID)->count();
      if (!\App\Land::doTheyOwn('plains', $contractorID)){
        $currentlyLeasing = \App\Lease::areTheyAlreadyLeasing('plains', $contractorID);
        if ($currentlyLeasing){
          $landBonus = \App\Lease::howManyAreTheyLeasing('plains', $contractorID);
          $leaseStatus = \App\Lease::use('plains', $contractorID);
        }
        if ($leaseStatus == false || !$currentlyLeasing){
          return [
            'error' => "You don't have access to any plains. Sorry."
          ];
        }
      }
      $production = 100;
      if ($robot == null){
        $production = $action->rank * 100;
      }
      $fieldType = \App\BuildingTypes::fetchByName($itemName . ' Field');
      if (\App\Buildings::howManyFieldsForThisLandType('plains', $contractorID) >= $landBonus){
        return [
          'error' => "Unfortunately, the number of " . $itemName . " Fields you can have are limited to the number of Plains you have access to and you're maxed out."
        ];
      }
      $field = new \App\Buildings;
      $field->buildingTypeID = $fieldType->id;
      $field->userID = $contractorID;
      $field[$whichVarName[$actionName]] = $production;
      $field->harvestAfter = date("Y-m-d H:i:s", strtotime('+24 hours'));
      $field->save();
      $status = "<span class='actionInput'>" . $electricityCaption . $foodCaption . "</span> &rarr; "
        . $itemName . " Field: <span class='fp'>+1</span>";


    } else if ($actionName == 'pump-oil'){
      $buildingCaption = \App\Buildings::use('Oil Well', $contractorID);
      if (isset($buildingCaption['error'])){
        return $buildingCaption;
      }
      $landResource = \App\Land::takeResource('Oil',  $agentID, $production,
        true);
      if ($landResource != true){
        return $landResource;
      }
      $itemCaption = \App\Items::use(\App\Items
        ::fetchActionItemInput($actionName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $output = \App\Items::make('Oil', $production, $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status']
        . $buildingCaption . "</span> &rarr; " . $output;


    } else if ($actionName == 'refine-oil'){
      $buildingCaption = \App\Buildings::use('Oil Refinery', $contractorID);
      if (isset($buildingCaption['error'])){
        return $buildingCaption;
      }
      $output = \App\Items::make('Jet Fuel', 1 * $production, $contractorID,
        $agentID);
      $output .= \App\Items::make('Gasoline', 5 * $production, $contractorID,
        $agentID);
      $output .= \App\Items::make('Diesel Fuel', 4 * $production, $contractorID,
        $agentID);
      $itemCaption = \App\Items
        ::use(\App\Items::fetchActionItemInput($actionName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status']
        . $buildingCaption . "</span> &rarr; " . $output;



    } else if ($actionName == 'smelt-copper' || $actionName == 'smelt-iron'
      || $actionName == 'smelt-steel'){
      $itemInputArr = ['smelt-copper' => 'Copper Ore',
        'smelt-iron' => 'Iron Ore', 'smelt-steel' => 'Iron Ingots'];
      $coal = Items::fetchByName('Coal', $contractorID);
      $electricity = Items::fetchByName('Electricity', $contractorID);
      $input = Items::fetchByName($itemInputArr[$actionName], $contractorID);
      if ($input->quantity < 10){
        return ['error' => "You don't have enough "
          . $itemInputArr[$actionName] . "."];
      } else if(!\App\Buildings
          ::doTheyHaveAccessTo('Small Furnace', $contractorID)
        && !\App\Buildings
          ::doTheyHaveAccessTo('Large Furnace', $contractorID)
        && \App\Buildings
          ::doTheyHaveAccessTo('Electric Arc Furnace', $contractorID)
        && ($electricity->quantity < 1000 || $input->quantity < 1000)){
        return [
          'error'
            => "You have an Electric Arc Furnace but don't have enough Electricity or "
            . $itemInputArr[$actionName] . "."
        ];
      } else if ((!\App\Buildings
        ::doTheyHaveAccessTo('Electric Arc Furnace', $contractorID)
      || (\App\Buildings
        ::doTheyHaveAccessTo('Electric Arc Furnace', $contractorID)
      && ($input->quantity < 1000 || $electricity->quantity < 1000)))
      && (\App\Buildings
        ::doTheyHaveAccessTo('Small Furnace', $contractorID)
      || \App\Buildings::doTheyHaveAccessTo('Large Furnace', $contractorID))
      && $coal->quantity < 10){
        return [
          'error'
            => "You have a Small Furnace  or Large Furnace but don't have enough Coal."
        ];
      }
      $buildingName = 'Small Furnace';
      $modifier = 10;
      $productionModifier = 1;
      if (\App\Buildings
        ::doTheyHaveAccessTo('Electric Arc Furnace', $contractorID)
        && $electricity->quantity >= 1000 && $input->quantity >= 1000){
          $modifier = 1000;
          $productionModifier = 100;
          $buildingName = 'Electric Arc Furnace';
      } else if (\App\Buildings
        ::doTheyHaveAccessTo('Large Furnace', $contractorID)){
        if ($input->quantity >= 100 && $coal->quantity >= 100){
          $modifier = 100;
          $productionModifier = 10;
          $buildingName = 'Large Furnace';
        }
        if (!\App\Buildings
        ::doTheyHaveAccessTo('Small Furnace', $contractorID)){
          $buildingName = 'Large Furnace';
        }
      }
      $production = $productionModifier;
      if ($robot == null){
        $production = $action->rank  * $productionModifier;
      }
      $buildingCaption = \App\Buildings::use($buildingName, $contractorID);
      if (isset($buildingCaption['error'])){
        return $buildingCaption;
      }
      $itemCaption = \App\Items::use(\App\Items
        ::fetchActionItemInput($actionName . $buildingName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $output = \App\Items::make(\App\Items::fetchItemNameForAction($actionName),
        $production, $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status']
        . $buildingCaption
        . "</span> &rarr; " . $output;



    } else if ($actionName == 'transfer-electricity-from-solar-power-plant'){
      $powerPlant = \App\Buildings::fetchByName('Solar Power Plant', $contractorID);
      if ($powerPlant->electricity < 1){
        return ['error' => "There's no Electricity in your Solar Power Plant."];
      }
      $electricity = \App\Items::fetchByName('Electricity', $contractorID);
      $production *= $powerPlant->electricity;
      $powerPlant->electricity = 0;
      $powerPlant->save();
      $buildingCaption = \App\Buildings::use('Solar Power Plant', $contractorID);
      if (isset($buildingCaption['error'])){
        return $buildingCaption;
      }
      $output = \App\Items::make('Electricity', $production,
        $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $buildingCaption
        . "</span> &rarr; " . $output;


    } else if ($actionName == 'gather-stone' || $actionName == 'gather-wood'
      || $actionName == 'hunt'){

      $whatMade = \App\Items
        ::make(\App\Items::fetchItemNameForAction($actionName), $production,
        $contractorID, $agentID);
      $status = $whatMade;
      if ($foodCaption != ''){
        $status = "<span class='actionInput'>" . $electricityCaption . $foodCaption
          . "</span> &rarr; " . $whatMade;
      }

    } else {
      if (isset($reqBuildings[0])){
        $buildingCaption = \App\Buildings::use($reqBuildings[0], $contractorID);
      }
      if (isset($buildingCaption['error'])){
        return $buildingCaption;
      }
      $itemCaption = \App\Items::use(\App\Items
        ::fetchActionItemInput($actionName), $contractorID);
      if (isset($itemCaption['error'])){
        return ['error' => $itemCaption['error']];
      }
      $output = \App\Items::make(\App\Items
        ::fetchItemNameForAction($actionName), $production,
        $contractorID, $agentID);
      $status =  "<span class='actionInput'>" . $electricityCaption . $foodCaption . $itemCaption['status']
        . $buildingCaption . "</span> &rarr; " . $output;
    }

    if ($robotID == null){
      \App\Labor::doAction($agentID, $action->id);
    }
    if ($robot == null && !$offline){
      $user = \App\User::find($agentID);
      $user->lastAction = date("Y-m-d H:i:s");
      $user->save();
    }
    return ['status' => $status ];
  }




  public static function doTheyHaveEnoughToBuild($buildingName){
    $buildingCosts = \App\BuildingTypes::fetchBuildingCost($buildingName);
    foreach ($buildingCosts as $material => $cost){
      $item = Items::fetchByName($material, \Auth::id());
      if ($item == null){
        \App\History::new(5, 'bug', $buildingName . ": " . $material);
      }
      if($item->quantity < $cost){
        return false;
      }
    }
    return true;
  }

  public static function fetch($userID){
      return [
        'buildings' => \App\Buildings::fetchBuildingsYouCanBuild(),
        'possible'  =>\App\Actions::fetchActionable($userID, true, null),
        'robots'    => \App\Actions::fetchRobotActions(),
        'unlocked'  =>\App\Actions::fetchUnlocked($userID, false),
      ];
  }

  public static function fetchActionable($userID, $onlyUnlocked, $justThisOne){

    $actionable = [];
    $labor = \App\Labor::where('userID', $userID)->first();
    $wearingRadiationSuit = false;
    if ($labor == null){
      \App\History::new(5 , 'bugs', "fetchActionable being called and labor is null: " . $userID . " \ " . $onlyUnlocked . " \ " . $justThisOne);
      return null;
    }
    if ($labor != null && $labor->alsoEquipped != null){
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
    $actions = \App\ActionTypes::all();
    if ($onlyUnlocked){
      $actions = \App\Actions::fetchUnlocked($userID, true);
    }
    foreach ($actions as $action){
      if ($justThisOne != null){
        $action = $justThisOne;
      } else if (!$onlyUnlocked){
        $action = $action->name;
      }
      $reqBuildings = \App\Buildings
        ::whichBuildingsDoTheyHaveAccessTo($action, \Auth::id());
      $coveredActions = ['chop-tree', 'cook-meat', 'cook-flour',
        'harvest-herbal-greens', 'harvest-plant-x', 'harvest-wheat',
        'harvest-rubber', 'make-book', 'mill-wheat', 'mill-log', 'mine-sand',
        'mine-coal', 'mine-stone', 'mine-iron-ore', 'mine-copper-ore',
        'mine-uranium-ore', 'plant-rubber-plantation', 'plant-wheat-field',
        'plant-herbal-greens-field', 'plant-plant-x-field', 'program-robot',
        'smelt-copper', 'smelt-iron', 'smelt-steel',
        'transfer-electricity-from-solar-power-plant'
      ];
      if ($action == 'chop-tree'
        && count(\App\Equipment::whichOfTheseCanTheyUse(\App\Equipment::whichEquipment($action), \Auth::id())) > 0
        && Land::doTheyHaveAccessTo('forest')){
        $actionable[] = $action;

      } else if ($action == 'cook-meat'
        && ((\App\Items::doTheyHave('Meat', 1)
          && \App\Items::doTheyHave('Wood', 1)
          && \App\Buildings::doTheyHaveAccessTo('Campfire', Auth::id()))
        || (\App\Items::doTheyHave('Meat', 10)
          && \App\Items::doTheyHave('Wood', 5)
          && \App\Buildings::doTheyHaveAccessTo('Kitchen', Auth::id()))
        || (\App\Items::doTheyHave('Meat', 100)
          &&  \App\Items::doTheyHave('Electricity', 100)
          && \App\Buildings::doTheyHaveAccessTo('Food Factory', Auth::id())))){
        $actionable[] = $action;

      } else if ($action == 'cook-flour'
      && ((\App\Items::doTheyHave('Flour', 1)
        && \App\Items::doTheyHave('Wood', 1)
        && \App\Buildings::doTheyHaveAccessTo('Campfire', Auth::id()))
      || (\App\Items::doTheyHave('Flour', 10)
        && \App\Items::doTheyHave('Wood', 10)
        && \App\Buildings::doTheyHaveAccessTo('Kitchen', Auth::id()))
      || (\App\Items::doTheyHave('Flour', 100)
        &&  \App\Items::doTheyHave('Electricity', 100)
        && \App\Buildings::doTheyHaveAccessTo('Food Factory', Auth::id())))){
        $actionable[] = $action;

      } else if ($action == 'harvest-herbal-greens'
        && \App\Buildings::canTheyHarvest('Herbal Greens Field', Auth::id())){
        $actionable[] = $action;

      } else if ($action == 'harvest-plant-x'
        && \App\Buildings::canTheyHarvest('Plant X Field', Auth::id())){
        $actionable[] = $action;

      } else if ($action == 'harvest-wheat'
        && \App\Buildings::canTheyHarvest('Wheat Field', Auth::id())){
        $actionable[] = $action;

      } else if ($action == 'harvest-rubber'
        && \App\Buildings::canTheyHarvest('Rubber Plantation', Auth::id())){
        $actionable[] = $action;

      } else if ($action == 'make-book'
        &&  \App\Items::doTheyHave('Paper', 100)
        && $labor->availableSkillPoints < 1){
        $actionable[] = $action;

      } else if ($action == 'mill-wheat'
      && ((Equipment::doTheyHave('Handmill', Auth::id())
        && Items::doTheyHave('Wheat', 10))
        || (\App\Buildings::doTheyHaveAccessTo('Gristmill', Auth::id())
        && Items::doTheyHave('Wheat', 100)))){

        $actionable[] = $action;

      } else if ($action == 'mill-log'
        && ((Equipment::doTheyHave('Saw', Auth::id())
          && Items::doTheyHave('Logs', 1))
          || (\App\Buildings::doTheyHaveAccessTo('Sawmill', Auth::id())
          && Items::doTheyHave('Logs', 10)))){
        $actionable[] = $action;

      } else if (($action == 'mine-sand')
        && Land::doTheyHaveAccessTo('desert')
        && count(\App\Equipment::whichOfTheseCanTheyUse(\App\Equipment::whichEquipment($action), \Auth::id())) > 0){
        $actionable[] = $action;

      } else if (($action == 'mine-coal' || $action == 'mine-stone'
        || $action == 'mine-iron-ore' || $action == 'mine-copper-ore')
        && Land::doTheyHaveAccessTo('mountains')
        && count(\App\Equipment::whichOfTheseCanTheyUse(\App\Equipment::whichEquipment($action), \Auth::id())) > 0){
        $actionable[] = $action;

      } else if ($action == 'mine-uranium-ore'
        && Land::doTheyHaveAccessTo('mountains')
        && count(\App\Equipment::whichOfTheseCanTheyUse(\App\Equipment::whichEquipment($action), \Auth::id())) > 0
        && $wearingRadiationSuit){
        $actionable[] = $action;

      } else if ($action == 'plant-rubber-plantation'
        && \App\Buildings::howManyBuildingsAndFieldsDoTheyHave($userID)
        < \App\User::find(Auth::id())->buildingSlots
        && Land::doTheyHaveAccessTo('jungle')
        && \App\Buildings::howManyFieldsForThisLandType('jungle', $userID)
        < \App\Buildings::howManyFieldsCanTheyHave('jungle', $userID)){
        $actionable[] = $action;

      } else if (($action == 'plant-wheat-field'
      || $action == 'plant-herbal-greens-field'
      || $action == 'plant-plant-x-field')
        && \App\Buildings::howManyBuildingsAndFieldsDoTheyHave($userID)
        < \App\User::find(Auth::id())->buildingSlots
        && Land::doTheyHaveAccessTo('plains')
        && \App\Buildings::howManyFieldsForThisLandType('plains', $userID)
          < \App\Buildings::howManyFieldsCanTheyHave('plains', $userID)){
        $actionable[] = $action;

      } else if ($action == 'program-robot'
        && \App\Items::doTheyHave('Robots', 1)){
        $actionable[] = $action;

      } else if ($action == 'smelt-copper'
      && ((\App\Buildings::doTheyHaveAccessTo('Electric Arc Furnace', Auth::id())
        && \App\Items::doTheyHave('Copper Ore', 1000)
        && \App\Items::doTheyHave('Electricity', 1000))
      || (\App\Buildings::doTheyHaveAccessTo('Small Furnace', Auth::id())
        && \App\Items::doTheyHave('Copper Ore', 10)
        && \App\Items::doTheyHave('Coal', 10))
      || (\App\Buildings::doTheyHaveAccessTo('Large Furnace', Auth::id())
        && \App\Items::doTheyHave('Copper Ore', 100)
        && \App\Items::doTheyHave('Coal', 100)))){
        $actionable[] = $action;

      } else if ($action == 'smelt-iron'
        && ((\App\Buildings::doTheyHaveAccessTo('Electric Arc Furnace', Auth::id())
          && \App\Items::doTheyHave('Iron Ore', 1000)
          && \App\Items::doTheyHave('Electricity', 1000))
        || (\App\Buildings::doTheyHaveAccessTo('Small Furnace', Auth::id())
          && \App\Items::doTheyHave('Iron Ore', 10)
          && \App\Items::doTheyHave('Coal', 10))
        || (\App\Buildings::doTheyHaveAccessTo('Large Furnace', Auth::id())
          && \App\Items::doTheyHave('Iron Ore', 100)
          && \App\Items::doTheyHave('Coal', 100)))){
        $actionable[] = $action;

      } else if ($action == 'smelt-steel'
      && ((\App\Buildings::doTheyHaveAccessTo('Electric Arc Furnace', Auth::id())
        && \App\Items::doTheyHave('Iron Ingots', 1000)
        && \App\Items::doTheyHave('Electricity', 1000))
      || (\App\Buildings::doTheyHaveAccessTo('Small Furnace', Auth::id())
        && \App\Items::doTheyHave('Iron Ingots', 10)
        && \App\Items::doTheyHave('Coal', 10))
      || (\App\Buildings::doTheyHaveAccessTo('Large Furnace', Auth::id())
        && \App\Items::doTheyHave('Iron Ingots', 100)
        && \App\Items::doTheyHave('Coal', 100)))){
        $actionable[] = $action;

      } else if ($action == 'transfer-electricity-from-solar-power-plant'
        && \App\Buildings::doTheyHaveAccessTo('Solar Power Plant', Auth::id())
        && $solarElectricity > 0){
        $actionable[] = $action;

      } else if (!in_array($action, $coveredActions)
        && ($reqBuildings === null || !empty($reqBuildings))
        && \App\Items::doTheyHaveEnoughFor($action)){
        $actionable[] = $action;
      }

      if ($justThisOne != null){
        break;
      }
    }
    return $actionable;
  }

  public static function fetchBanned(){
    return [
      'build', 'make-book', 'repair', 'program-robot'
    ];

  }

  public static function fetchByName($userID, $name){
    $actionType = \App\ActionTypes::where('name', $name)->first();
    if ($actionType == null){
      \App\History::new(5, 'bugs', $userID .  ": " . $name . " is not a valid Action type. BUG");
    }
    return \App\Actions::where('actionTypeID', $actionType->id)
      ->where('userID', $userID)->first();

  }

    public static function fetchUnlocked($userID, $asArr){
      $actions = \App\Actions
        ::join('action_types', 'actions.actionTypeID', 'action_types.id')
        ->where('userID', $userID)->where('unlocked', true)
        ->select('name', 'actions.id', 'actionTypeID', 'totalUses', 'nextRank',
        'rank', 'unlocked')->get();
      if ($asArr){
        $actionArr = [];
        foreach ($actions as $action){
          $actionArr[] = $action->name;
        }
        return $actionArr;
      }
      return $actions;
    }

    public static function fetchBaseProduction($actionName, $robotID, $userID){
      $productionArr = [
        'convert-coal-to-carbon-nanotubes'      => 10,
        'convert-corpse-to-genetic-material'    => 100,
        'convert-corpse-to-Bio-Material'        => 10,
        'convert-herbal-greens-to-Bio-Material' => 10,
        'convert-meat-to-Bio-Material'          => 10,
        'convert-plant-x-to-Bio-Material'       => 10,
        'convert-sand-to-silicon'               => 10,
        'convert-uranium-ore-to-plutonium'      => 10,
        'convert-wheat-to-Bio-Material'         => 10,
        'convert-wood-to-carbon-nanotubes'      => 10,
        'convert-wood-to-coal'                  => 100,
        'generate-electricity-with-coal'        => 100,
        'generate-electricity-with-plutonium'   => 100000,
        'hunt'                                  => 2,
        'make-paper'                            => 10,
        'mill-log'                              => 100,
        'plant-rubber-plantation'               => 100,
        'plant-wheat-field'                     => 100,
        'plant-plant-x-field'                   => 100,
        'plant-herbal-greens-field'             => 100,
        'pump-oil'                              => 100,
      ];
      $baseProduction = 1;
      if (isset($productionArr[$actionName])){
        $baseProduction = $productionArr[$actionName];
      }
      if ($robotID != null){
        return $baseProduction;
      }
      $action = \App\Actions::fetchByName($userID, $actionName);
      return $baseProduction * $action->rank;
    }

    public static function fetchRobotActions(){
      $bannedActions = \App\Actions::fetchBanned();
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


    public static function offline(){
      $users = \App\User::where('minutes', '>', 0)
        ->where('lastAction', '<', date("Y-m-d H:i:s", strtotime("-5 minutes")))
        ->whereNotNull('action')->get();
      foreach($users as $user){
        for($i = 0; $i < 15; $i++){
          $msg = \App\Actions::do($user->action, $user->id, $user->id, null,
            true, true);
          if (isset($msg['error'])){
            $user->action = null;
            $user->save();
            \App\History::new($user->id, 'action',
              "Offline Automation Stopped Due To: " . $msg['error']);
            break;
          }
          \App\History::new($user->id, 'action', $msg['status']);
        }
        $user->minutes--;
        $user->save();
        if ($user->minutes == 0){
          $user->action = null;
          $user->save();
          continue;
        }
      }
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
