<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      echo json_encode(\App\Contracts::fetch());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

      $contract = \App\Items::fetchByName('Contracts', Auth::id());
      if ($contract->quantity < 1){
        echo "You need to have a contract in order to create one.";
        return;
      }
      $defaultCategory = $request->category;
      return view('Contracts.create', [
        'defaultCategory' => $defaultCategory,
        'itemTypes' => \App\ItemTypes::orderBy('name')->get(),
        'items' => \App\Items::fetchInventory(),
        'buildings' => \App\Buildings::fetchBuilt(),
        'constructionSkill' => \App\Skills::fetchByIdentifier('construction', \Auth::id()),
        'hireableActions' => \App\ActionTypes::all(),
        'freelanceActions' => \App\Actions::fetchUnlocked(\Auth::id()),
        'labor' => \App\Labor::fetch(),
        'land' => \App\Land::where('userID', Auth::id())->get(),
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      echo $request->buildingID;
      $contractItem = \App\Items::fetchByName('Contracts', Auth::id());
      if ($contractItem->quantity < 1){
        echo "You don't have any contracts.";
        return;
      }
      $user = Auth::user();

      if (($request->category != 'freelance' && $request->category != 'sellLand'
        && $request->category != 'sellOrder' ) && $user->clacks < $request->price){
        echo "You don't have enough clacks to cover the contract price.";
        return;

      } else if ($request->category == 'freelance'
        && ($request->until == 'food' && \App\Items::fetchByName('Food', Auth::id()) == 0)){
        echo "You can't set up a contract to continue until you run out of food when you have no food. Sorry.";
        return;
      } else if ($request->category == 'construction'
        && (\App\Skills::fetchByIdentifier('construction', \Auth::id())->rank < 1)){
        echo "You aren't able to do Construction.";
        return;
      } else if ($request->category == 'repair' && ($user->clacks < $request->price
        || count(\App\Buildings::fetch()['built']) < 1)){
        echo "You either don't have any buildings or don't have enough clacks to cover the contract price.";
        return;
      } else if ($request->category == 'buyOrder'
        && (\App\ItemTypes::find($request->itemTypeID) == null
        || $user->clacks < $request->price)){
        echo "You don't have enough money to cover this. <a href='" . route('contracts.create') . "'>back</a>";
        return;
      } else if ($request->category == 'sellOrder'
        && (\App\ItemTypes::find($request->itemTypeID) == null
        || \App\Items:: fetchTotalQuantity(Auth::id()) < 1
        || \App\Items::fetchByItemTypeID($request->itemTypeID)->quantity < 1)){
          echo "You don't appear to have this item. <a href='" . route('contracts.create') . "'>back</a>";
          return;
      } else if ($request->category == 'sellLand'
        && !\App\Land::canTheyGetRidOfThisLand($request->landID)){
        echo "You can't get rid of this land because you have too many
          buildings. <a href='" . route('contracts.create') . "'>back</a>";
        return ;

    } else if ($request->category == 'lease'
      && !\App\Land::doTheyOwn($request->landType, \Auth::id())){
      echo "You don't own this type of land. (" . $request->landType . ")";
      return ;
    } else if ($request->category == 'leaseBuilding'){
      $building = \App\Buildings::find($request->buildingID);
      if ($building->uses == 0){
        echo "There aren't any uses left for this building.";
        return ;
      }

    }
      $possibleCategories = ['hire', 'freelance','buyOrder', 'sellOrder', 'buyLand', 'sellLand', 'construction', 'repair', 'reproduction', 'lease', 'leaseBuilding'];
      if (!in_array($request->category, $possibleCategories) || !filter_var($request->price, FILTER_VALIDATE_INT)){
        echo "This doesn't apper to be a valid type of contract. ";
        return;
      }

      $contract = new \App\Contracts;
      $contract->category = $request->category;
      $contract->userID = Auth::id();
      $contract->price = $request->price;

      if ($request->category == 'leaseBuilding'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'leaseBuilding')
          ->where('buildingID', $request->buildingID)->first();
        if ($currentContract != null){
          echo "You already have a contract to lease this building";
          return;
        }
        $building = \App\Buildings::find($request->buildingID);
        $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
        $contract->buildingName = $buildingType->name;
        $contract->buildingID = $request->buildingID;
      } else if ($request->category == 'lease'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'lease')
          ->where('landType', $request->landType)->first();
        if ($currentContract != null){
          echo "You already have a contract to lease land (" . $request->landType . ")";
          return;
        }
        $contract->landType = $request->landType;
      } else if ($request->category == 'buyLand'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'buyLand')
          ->where('landType', $request->landType)->first();
        if ($currentContract != null){
          echo "You already have a contract to buy land (" . $request->landType . ")";
          return;
        }
        $contract->landType = $request->landType;
        $contract->until = $request->until;
        if ($request->until == 'finite'){
          $contract->condition = $request->condition;
          $contract->conditionFulfilled = 0;

        }
      } else if ($request->category == 'sellLand'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'sellLand')
          ->where('landID', $request->landID)->first();
        if ($currentContract != null){
          echo "You already have a contract to sell parcel #" . $request->landID;
          return;
        }
        $contract->landID = $request->landID;
      } else if ($request->category == 'hire'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'hire')
          ->where('action', $request->action)->first();
        if ($currentContract != null){
          echo "You already have a contract to hire someone to " . $request->action;
          return;
        }
        if ($request->whichPrice == 'pricePerSkill'){
          $contract->pricePerSkill = true;
        }
        $contract->action = $request->action;
        $contract->minSkillLevel = $request->minSkillLevel;
        $contract->until = $request->until;
        if ($request->until == 'finite'){
          $contract->condition = $request->condition;
          $contract->conditionFulfilled = 0;
        }
      } else if ($request->category == 'freelance'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'freelance')
          ->where('action', $request->action)->first();
        if ($currentContract != null){
          echo "You already have a contract to freelance " . $request->action;
          return;
        }

        $contract->action = $request->action;
        $contract->until = $request->until;
        if ($request->until == 'finite'){
          $contract->condition = $request->condition;
          $contract->conditionFulfilled = 0;
        }
      } else if ($request->category == 'construction'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'construction')->first();
        if ($currentContract != null){
          echo "You already have a contract to build this.";
          return;
        }
        $contract->minSkillLevel = \App\Skills::fetchByIdentifier('construction', \Auth::id())->rank;
      } else if ($request->category == 'repair'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', 'repair')
          ->where('buildingID', $request->buildingID)->first();
        if ($currentContract != null){
          echo "You already have a contract to repair this.";
          return;
        }
        $contract->buildingID = $request->buildingID;
        $contract->category = $request->category . $request->repairIf;
        $contract->until = $request->until;
        if ($request->until == 'finite'){
          $contract->condition = $request->condition;
          $contract->conditionFulfilled = 0;
        }
      } else if ($request->category == 'buyOrder' || $request->category == 'sellOrder'){
        $currentContract = \App\Contracts::where('userID', Auth::id())
          ->where('active', 1)->where('category', $request->category)
          ->where('itemTypeID', $request->itemTypeID)->first();
        if ($currentContract != null){
          echo "You already have a contract to buy or sell this item.";
          return;
        }
        $contract->itemTypeID = $request->itemTypeID;
        $contract->until = $request->until;
        if ($contract->until != 'gone'){
          $contract->condition = $request->condition;
          $contract->conditionFulfilled = 0;

        }
      }
      $contractItem->quantity--;
      $contractItem->save();

      $contract->save();
      $status = "";
      if ($request->category == 'buyOrder'){
        $status = \App\Contracts::anyoneSelling($contract->id);
      } else if ($request->category == 'sellOrder'){
        $status = \App\Contracts::anyoneBuying($contract->id);
      }
      \App\History::new(Auth::id(), 'contract', "You created a new contract. ("
        . $request->category . ") " . $status );

      return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $contract = \App\Contracts::find($id);
      if ($contract == null){
        return;
      }
      $status = "";
      $clacks = 0;
      if ($request->type == 'lease'){
        $contract = \App\Contracts::find($id);
        $contractor = \App\User::find($contract->userID);
        $user = \App\User::find(\Auth::id());
        if ($user->clacks < $contract->price){
          echo json_encode(['error' => "You don't have enough money. Sorry."]);
          return;
        } else if (!\App\Land::doTheyOwn($contract->landType, $contract->userID)){
          echo json_encode(['error' => "This contractor no longer owns a "
            . $contract->landType . ". Sorry."]);
          \App\History::new($contractor->id, 'contract', "You no longer have a " . $contract->landType . " so your lease contract was cancelled.");
          $contract->active = 0;
          $contract->save();
          return;
        } else if (\App\Land::doTheyOwn($contract->landType, \Auth::id())){
          echo json_encode(['error' => "You already own a " . $contract->landType . " so you don't need to lease this. "]);
          return;
        } else if (\App\Lease::areTheyAlreadyLeasing($contract->landType, \Auth::id())){
          echo json_encode(['error' => "You're already leasing a " . $contract->landType . ". Sorry."]);
          return;
        }

        $newLease = new \App\Lease;
        $newLease->contractID = $contract->id;
        $newLease->landType = $contract->landType;
        $newLease->userID = \Auth::id();
        $newLease->save();
        $status = "You are now leasing " . $contract->landType . " from " . $contractor->name . " for " . number_format($contract->price) . " clack(s) per use. You may cancel at any time.";
        $clacks = $user->clacks;
      } else if ($request->type == 'reproduction'){

        $surrogate = \Auth::user();
        $surrogateLabor = \App\Labor::where('userID', $surrogate->id)->first();
        $contractor = \App\User::find($contract->userID);
        if ($contractor->clacks < $contract->price){
          echo json_encode(['error' => "The contractor doesn't have enough money. Sorry."]);
          \App\History::new($contractor->id, 'contract', "You ran out of money for your reproduction contract. It was cancelled.");
          $contract->active=false;
          $contract->save();
          return;
        }
        $children = \App\Items::fetchByName('Children', $contractor->id);
        $children->quantity++;
        $children->save();
        $contractor->clacks -= $contract->price;
        $contractor->save();
        $surrogateLabor->escrow += $contract->price;
        $surrogateLabor->rebirth = true;
        $surrogateLabor->save();

        $contract->active = false;
        $contract->save();
        \App\History::new($contractor->id, 'contract', $surrogate->name
          . " created a child for you for " . number_format($contract->price)
          . " clack(s). You now have " . number_format($contractor->clacks)
          . " clack(s).");
        $status = "You created a child for " . $contractor->name . " for "
          . number_format($contract->price)
          . " clack(s). This will be placed into your account after you have "
          . "finished rebirth. (So you won't have to pay the estate tax on it.)" ;




      } else if ($request->type == 'sellLand'){
        $seller = \App\User:: find($contract->userID);
        $buyer = Auth::user();
        $land = \App\Land::find($contract->landID);
        if ($buyer->clacks < $contract->price){
          echo json_encode(['error' => "You don't have enough money."]);
          return;
        }

        $buyer->clacks -= $contract->price;
        $buyer->save();
        $seller->clacks += $contract->price;
        $seller->save();
        $land->userID = $buyer->id;
        $land->valuation = $contract->price;
        $land->save();
        \App\Land::integrityCheck($seller->id);
        \App\Land::integrityCheck($buyer->id);
        $contract->active = false;
        $contract->save();
        \App\History::new($seller->id, 'contract', "You sold land parcel #" . $land->id
          . " to " . $buyer->name . " for " . number_format($contract->price)
          . ". You now have " . number_format($seller->clacks) .  ' clack(s).');
        $status = "You bought land parcel #" . $land->id
          . " from " . $seller->name . " for " . number_format($contract->price)
          . ". You now have " . number_format($buyer->clacks) . ' clack(s).';

      } else if ($request->type == 'buyLand'){
        $buyer = \App\User:: find($contract->userID);
        $seller = Auth::user();

        if (!\App\Land::doTheyHaveAccessTo($contract->landType)){
          echo json_encode(['error' => "You do not have this type of land."]);
          return;
        } else if ($buyer->clacks < $contract->price){
          echo json_encode(['error' => "The buyer no longer has enough money."]);
          \App\History::new($buyer->id, 'contract', "You don't have enough money to buy "
            . $contract->landType . " for " . $contract->price
            . " clack(s) anymore, so it was cancelled.");
          $contract->active = false;
          $contract->save();
          return;
        }
        $land = \App\Land::where('userID', Auth::id())->where('type', $contract->landType)->first();
        $land->userID = $buyer->id;
        $land->valuation = $contract->price;
        $land->save();
        $status = "You sold land parcel #" . $land->id
          . " to " . $buyer->name . " for " . number_format($contract->price)
          . ". You now have " . number_format($seller->clacks) . " clack(s).";
        $seller->clacks += $contract->price;
        $clacks = $seller->clacks;
        $seller->save();
        $buyer->clacks -= $contract->price;
        $buyer->save();
        \App\Land::integrityCheck($seller->id);
        \App\Land::integrityCheck($buyer->id);
        if ($buyer->clacks < $contract->price){
          \App\History::new($buyer->id, 'contract', "You ran out of money aftering buying "
            . $contract->landType . " for " . $contract->price
            . " clack(s).");
          $contract->active = false;
        }
        if ($contract->until == 'finite'){
          $contract->conditionFulfilled ++;
          if ($contract->conditionFulfilled >= $contract->condition){
            $contract->active = false;
          }
        }
        $contract->save();
        \App\History::new($buyer->id, 'contract', "You bought land parcel #" . $land->id
          . " from " . $seller->name . " for " . number_format($contract->price)
          . ". You now have " . number_format($buyer->clacks) . " clack(s).");

      } else if ($request->type == 'repair'){

        $contractor = Auth::user();
        $builder = \App\User::find($contract->userID);

        $building = \App\Buildings::find($request->buildingID);
        $buildingType = \App\BuildingTypes::find($building->buildingTypeID);
        $constructionSkill = \App\Skills::fetchByIdentifier('construction', $builder->id);
        if (!\App\BuildingTypes::canTheyRepair($buildingType->name)){
          echo json_encode(['error' => "You don't the necessary materials for them to repair this. (10% of build cost)"]);
          return;
        } else if ($constructionSkill->rank < $contract->minSkillLevel){
          echo json_encode(['error' => "They no longer have the necessary skill for this contract. Sorry. "]);
          \App\History::new($contract->userID, 'contract', "You no longer have the necessary Construction skill for your contract so it was cancelled.");
          $contract->active = false;
          $contract->save();
          return;
        } else if ($contractor->clacks < $contract->price){
          echo json_encode(['error' => "You don't have enough money for this contract. "]);
          return;
        } else if ($building->uses == $building->repairedTo){
          echo json_encode(['error' => "This building doesn't need to be repaired right now. "]);
          return;
        }

        $msg = \App\Buildings::repair($request->buildingID, $contract->userID, \Auth::id());

        if (isset($msg['error'])){
          echo json_encode([
            'error' => $msg['error']
          ]);
        } else {
          $status = $builder->name . " repaired " . $buildingType->name
            . " for you for " . number_format($contract->price)
            . " clack(s). You now have " . number_format($contractor->clacks) . " clack(s).$request->buildingID";
        }
        $contractor->clacks -= $contract->price;
        $contractor->save();
        $builder->clacks += $contract->price;
        $clacks = $builder->clacks;
        $builder->save();


      } else if ($request->type == 'construction'){
        $contractor = Auth::user();
        $builder = \App\User::find($contract->userID);
        $buildingName = $request->buildingName;
        $constructionSkill = \App\Skills::fetchByIdentifier('construction', $builder->id);

        if ($contractor->clacks < $contract->price){
          echo json_encode(['error' => "You don't have enough money."]);
          return;
        } else if($contractor->buildingSlots < 1){
          echo json_encode(['error' => "You don't have enough building slots."]);
          return;
        } else if ($constructionSkill->rank < $contract->minSkillLevel){
          echo json_encode(['error' => "They no longer have their construction skill at this level anymore. Sorry, we cancelled the contract."]);
          \App\History::new($builder->id, 'contract', "You no longer have the required Construction skill for this contract so it was cancelled.");
          $contract->active = false;
          $contract->save();
          return;
        }
        if (\App\Buildings::didTheyAlreadyBuildThis($buildingName, $contractor->id) && $buildingName != 'Warehouse' ){
          $building = fetchByName($buildingName, $contractor->id);
          $msg = \App\Buildings::rebuild($building->id, $builder->id, $contractor->id);
        } else {
          $msg = \App\Buildings::build($buildingName, $builder->id, $contractor->id);
        }


        if (isset($msg['error'])){
          echo json_encode(['error' => $msg['error']]);
          return;
        } else {
          $status = "You built " . $buildingName
            . " for " . $contractor->name . " for " . number_format($contract->price)
            . " clack(s).";
        }

        $contractor->clacks -= $contract->price;
        $contractor->save();
        $builder->clacks += $contract->price;
        $builder->save();
        $clacks = $builder->clacks;
        \App\History::new($contractor->id,  'contract', "You paid " . $builder->name . " to build a "
          . $buildingName . " for " . number_format($contract->price)
          . " clack(s). You now have " . number_format($contractor->clacks));



      } else if ($request->type == 'hire'){
        $employer = \App\User::find($contract->userID);
        $user = Auth::user();
        $cost = $contract->price;
        if ($contract->pricePerSkill){
          $cost = $contract->price * $skill->rank;
        }
        if ($employer->clacks < $cost){
          \App\History::new($contract->userID, 'contract', "Contract Cancelled: You ran out of money to hire people to " . $contract->action);
          echo json_encode(['error' => "The contractor did not have the necessary clacks for this contract."]);
          $contract->active=false;
          $contract->save();
          return;
        }
        $user->clacks += $cost;
        $clacks = $user->clacks;
        $user->save();
        $employer->clacks -= $cost;
        $employer->save();
        if ($employer->clacks < $contract->price){
          \App\History::new($contract->userID, 'contract', "Contract Cancelled: You ran out of money to hire people to " . $contract->action);
          $contract->active = false;
          $contract->save();
          return;
        }
        $msg = \App\Actions::do($contract->action, Auth::id(),
          $contract->userID, null);

        if (isset($msg['error'])){
          $status = $msg['error'];
        }  else {
          $status = $msg['status'];
          $clackCaption = ' clack. ';
          if ($user->clacks > 1){
            $clackCaption = ' clacks. ';
          }
          $status .= $employer->name . " hired you to  " . $contract->action
            . " for " . number_format($cost) . " clack(s). You now have " . number_format($user->clacks)  . $clackCaption;
        }
        if ($contract->until == 'finite'){
          $contract->conditionFulfilled++;
        }
        if ($contract->condition != null && $contract->conditionFulfilled >= $contract->condition){
          $contract->active = false;
        }
        $contract->save();
        $clackCaption = ' clack. ';
        if ($employer->clacks > 1){
          $clackCaption = ' clacks. ';
        }
        \App\History::new($employer->id,  'contract', "You paid " . $user->name . " to "
          . $contract->action . " for " . $cost
          . " clack(s). You now have " . number_format($employer->clacks) . $clackCaption);



      } else if ($request->type == 'freelance'){
        $status = "";
        $user = Auth::user();
        $freelancer = \App\User::find($contract->userID);
        $freelanceLabor = \App\Labor::where('userID', $contract->userID)->first();

        $user->clacks -= $contract->price;
        $clacks = $user->clacks;
        $user->save();
        $freelancer->clacks += $contract->price;
        $freelancer->save();
        $msg = \App\Actions::do($contract->action, $contract->userID, Auth::id(), null);
        if (isset($msg['error'])){
          $status = $msg['error'];
        }  else {
          $status = "You paid " . $freelancer->name . " to "
            . $contract->action . " for " . number_format($contract->price)
            . " clack(s). (" . $msg['status'] . ") You now have "
            . number_format($user->clacks) . " clack(s).";
        }


        if ($contract->until == 'food'){
          $food = \App\Items::fetchByName('Food', $freelancer->id);
          if ($food->quantity < 1){
            \App\History::new($freelanceLabor->id, 'contract', "You ran out of food so your freelance contract was cancelled.");
            $contract->active = false;

          }
        } else if ($contract->until == 'finite'){
          $contract->conditionFulfilled++;
        }
        if ($contract->condition != null && $contract->conditionFulfilled >= $contract->condition){
          $contract->active = false;
        }
        $contract->save();
        \App\History::new($freelancer->id, 'contract', $user->name . " hired you to  " . $contract->action
          . " for " . number_format($contract->price) . " clack(s). You now have " . number_format($freelancer->clacks));



      } else if ($request->type == 'buyFromSellOrder'){
        $itemType = \App\ItemTypes::find($contract->itemTypeID);
        $sellerItem = \App\Items::where('itemTypeID', $contract->itemTypeID)
          ->where('userID', $contract->userID)->first();
        $buyer = Auth::user();

        if ($buyer->clacks < $contract->price * $request->quantity){
          echo json_encode(['error'=> "You do not have enough clacks. "]);
          return;
        }
        if ($sellerItem->quantity < $request->quantity){
          if ($sellerItem->quantity == 0){
            \App\History::new($contract->userID, 'contract', "You ran out of items to sell so your contract selling " . $itemType->name . " was cancelled.");
            echo json_encode(['error'=> "The person selling the contract ran out of these items. Sorry."]);
            $contract->active = false;
            $contract->save();
            return;
          }
          echo json_encode(['error' => 'Sorry, they only have ' . $sellerItem->quantity . " items right now."]);
          return;
        }
        $seller = \App\User::find($contract->userID);
        $seller->clacks += $contract->price * $request->quantity;
        $seller->save();
        $sellerItem->quantity -= $request->quantity;
        $sellerItem->save();
        $buyerItem = \App\Items::where('itemTypeID', $contract->itemTypeID)
          ->where('userID', Auth::id())->first();
        $buyerItem->quantity += $request->quantity;
        $buyerItem->save();
        $buyer->clacks -= ($contract->price * $request->quantity);
        $buyer->save();
        $status = "You bought " . $request->quantity . " " . $itemType->name
          . " from " . $seller->name . " for " . number_format($contract->price)
          . " clack(s) each. You now have " . number_format($buyer->clacks) . ' clack(s).';

        if ($sellerItem->quantity < $request->quantity){
          \App\History::new($contract->userID, 'contract', "You ran out of items to sell so your contract selling " . $itemType->name . " was cancelled.");
          $contract->active = false;
          $contract->save();
          return;
        }
        if ($contract->until == 'sold'){
          $contract->conditionFulfilled += $request->quantity;
          $contract->save();
        } else if ($contract->until == 'earn'){
          $contract->conditionFulfilled += $contract->price * $request->quantity;
          $contract->save();
        }
        if ($contract->until != 'gone' && $contract->conditionFulfilled >= $contract->condition){
          $contract->active = false;
          $contract->save();
        }
        $clacks = $buyer->clacks;
        \App\History::new($seller->id, 'contract', "You sold " . $request->quantity . " " . $itemType->name
          . " to " . $buyer->name . " for " . $contract->price
          . " clack(s) each. You now have " . $seller->clacks . " clack(s).");


      } else if ($request->type == 'sellToBuyOrder'){
        $itemType = \App\ItemTypes::find($contract->itemTypeID);
        $sellerItem = \App\Items::where('itemTypeID', $contract->itemTypeID)
          ->where('userID', Auth::id())->first();
        $buyer =\App\User::find($contract->userID);

        if ($buyer->clacks < $contract->price * $request->quantity){
          \App\History::new($contract->userID, 'contract', "You ran out of money so your contract buying " . $itemType->name . " was cancelled.");
          $contract->active = false;
          $contract->save();
          return;
        }
        if ($sellerItem->quantity < $request->quantity){
          return;
        }
        $seller = Auth::user();
        $buyerItem = \App\Items::where('itemTypeID', $contract->itemTypeID)
          ->where('userID', $contract->userID)->first();
        if ($contract->until == 'bought' && $request->quantity
          + $contract->conditionFulfilled > $contract->condition){
          $seller->clacks += $contract->price *
            ($contract->condition - $contract->conditionFulfilled);
          $buyer->clacks -= $contract->price *
            ($contract->condition - $contract->conditionFulfilled);
          $buyerItem->quantity +=   ($contract->condition
            - $contract->conditionFulfilled);
          $sellerItem->quantity -+  ($contract->condition
            - $contract->conditionFulfilled);
          $status .= " (Because the amount you wanted to sell was higher than
            the contract fulfillment, you only sold "
            . ($contract->condition - $contract->conditionFulfilled) . ")";
          $contract->conditionFulfilled = $contract->condition;

          $contract->active = false;
          $contract->save();
        } else if ($contract->until == 'inventory'
          && $request->quantity + $buyerItem->quantity > $contract->condition){
          $status .= " (Because the amount you wanted to buy was more than the
            contract needed, you only sold " . ($contract->condition
            - $buyerItem->quantity) . ")";
          $seller->clacks += $contract->price * ($contract->condition
            - $buyerItem->quantity);
          $buyer->clacks -= $contract->price *  ($contract->condition
            - $buyerItem->quantity);
          $buyerItem->quantity +=   ($contract->condition - $buyerItem->quantity);
          $sellerItem->quantity -+  ($contract->condition - $buyerItem->quantity);

          $contract->active = false;
          $contract->save();
        } else {
          $seller->clacks += $contract->price * $request->quantity;
          $sellerItem->quantity -= $request->quantity;

          $buyerItem->quantity += $request->quantity;
          $buyer->clacks -= $contract->price * $request->quantity;


          if ($buyer->clacks < $contract->price * $request->quantity){
            \App\History::new($contract->userID, 'contract',
              "You ran out of money so your contract buying " . $itemType->name
              . " was cancelled.");
            $contract->active = false;
            $contract->save();
          }
          if ($contract->until == 'bought'){
            $contract->conditionFulfilled += $request->quantity;
            $contract->save();
          } else if ($contract->until == 'spend'){
            $contract->conditionFulfilled += $contract->price * $request->quantity;
            $contract->save();
          }
          if (($contract->until != 'gone'
            && $contract->conditionFulfilled >= $contract->condition)
            || ($contract->until == 'inventory'
            && $buyerItem->quantity >= $contract->condition)){
            $contract->active = false;
            $contract->save();
          }
        }
        $status = "You sold " . number_format($request->quantity) . " " . $itemType->name
          . " to " . $buyer->name . " for " . number_format($contract->price)
          . " clack(s) each. You now have " . number_format($seller->clacks) . " clack(s).";

        $sellerItem->save();
        $seller->save();

        $buyerItem->save();

        $buyer->save();
        $clacks = $seller->clacks;
        \App\History::new($buyer->id, 'contract', "You bought " . $request->quantity . " "
          . $itemType->name . " from " . $seller->name . " for " . number_format($contract->price)
          . " clack(s) each. You now have " . number_format($buyer->clacks) . " clack(s).");


      }
      \App\History::new(Auth::id(), 'contract', $status);
      echo json_encode([
        'status' => $status,
        'clacks' => $clacks,
        'buildingSlots' => \App\User::find(\Auth::id())->buildingSlots,
        'history' => \App\History::fetch(),
        'items' => \App\Items::fetch(),
        'labor' => \App\Labor::fetch(),
        'land' => \App\Land::fetch(),
        'leases' => \App\Lease::fetch(),
        'contracts' => \App\Contracts::fetch(),
        'actions' => \App\Actions::fetch(\Auth::id()),
        'land' => \App\Land::fetch(),
      ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract = \App\Contracts::find($id);
        if ($contract->category == 'lease'){
          \App\Lease::bad($contract->id, ' canceled');
        }
        $contract->active = false;

        $contract->save();
        $status = "You canceled this contract.";
        \App\History::new(Auth::id(), 'contract', $status);
        echo json_encode([
          'contracts' => \App\Contracts::fetch(),
          'history' => \App\History::fetch(),
          'status' => $status,
        ]);
    }
}
