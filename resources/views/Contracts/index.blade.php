@if ($canTheyCreateContract)
<div class='text-center mt-3 mb-3'>

  <a href='/contracts/create'>Post on market</a>
</div>
@endif
<div>
  <ul class="nav nav-tabs">
    <li class="nav-item ">
            <a class="nav-link market @if($filter == 'land') active @endif" href="#">land</a>
    </li>
    <li class="nav-item ">
      <a class="nav-link market @if($filter == 'labor') active @endif" href="#">labor</a>
    </li>
    <li class="nav-item ">
      <a class="nav-link market @if($filter == 'items') active @endif" href="#">items</a>
    </li>
    <li class="nav-item ">
      <a class="nav-link market @if($filter == 'buildings') active @endif" href='#'>buildings</a>
    </li>
    <li class="nav-item ">
      <a class="nav-link market @if($filter == 'mine') active @endif)" href='#'>mine</a>
    </li>
  </ul>
</div>

<div id='contractItemFilterDiv'  class='d-none'>
  Filter By Item: <select id='contractItemFilter'><option></option>
    @foreach($relevantItems as $itemTypeID => $itemName)
      <option value='{{$itemTypeID}}'>{{$itemName}}</option>
    @endforeach
  </select>
  <input type='checkbox' class='contractFilterByCategory ms-3' value='buyOrder' checked> buying
  <input type='checkbox' class='contractFilterByCategory ms-3' value='sellOrder' checked> selling
</div><div id='contractLandFilterDiv' class='d-none'>
  Filter By Land Type:
  <select id='contractLandFilter'>
    <option></option>
    @foreach ($landTypes as $landType)
      <option>{{$landType}}</option>
    @endforeach

  </select>
</div>
<div id='newContactInContracts' class='text-center'></div>

<div id='contractListings'>

@if (count($contracts) < 1 )

  <div class='text-center'>
  @if ($filter == null || $filter == 'mine')
    You don't have anything on the market yet. Buy or create a Contract to be able to setup an order on the market.
  @else
    No contracts yet.
  @endif
  </div>
@endif
@foreach ($contracts as $contract)
<?php
  $username = "You are ";
  if ($contract->userID != $userID){
    $username = \App\User::find($contract->userID)->name;
  }
?>
@if ($contract->itemTypeID != null)
<?php $itemName = \App\ItemTypes::find($contract->itemTypeID)->name; ?>
@endif
<div class="mt-3 {{$contract->category}}@if($contract->landType != null) {{$contract->landType}} @endif
  @if ($contract->userID != $userID)  notMyContract  @endif
  @if ($contract->category == 'buyOrder' || $contract->category == 'sellOrder')
  itemClass{{$contract->itemTypeID}}
  @endif contracts p-3">


  @if ($contract->category == 'buyLand')
    {{$username}} <span class='fw-bold'>buying</span> {{$contract->landType}} for
    {{number_format($contract->price)}} clack(s) each until
    @if ($contract->until == 'gone'){
      the money runs out.
    @elseif ($contract->until == 'finite')
      {{number_format($contract->condition)}} pieces of land bought [ bought
      {{number_format($contract->conditionFulfilled)}} so far ]
    @endif
    <div class='ms-3'>

    @if ($contract->userID == $userID)
      <button id='cancelContract-{{$contract->id}}'
        class='cancelContract btn btn-warning'>cancel</button>

    @elseif (in_array($contract->landType, $ownedLandTypes))
      <button id='buyLand-{{$contract->id}}-market' class='buyLand btn btn-success'>sell {{$contract->landType}}</button>
    @endif
  </div>



  @elseif ($contract->category == 'buyOrder')
    <?php $quantity = \App\Items::fetchByName($itemName, $userID)->quantity; ?>
    {{$username}} <span class='fw-bold'>buying</span>
    <span class='text-decoration-underline'>
    {{$itemName}}</span> for {{number_format($contract->price)}} clack(s) each until
    @if ($contract->until == 'gone')
       they run out of money or space"
    @elseif ($contract->until == 'bought')
      {{number_format($contract->condition)}} {{$itemName}} bought [ bought
      {{number_format($contract->conditionFulfilled)}} so far ]
    @elseif ($contract->until == 'inventory')
       they have a certain amount in inventory
    @elseif ($contract->until == 'spend')
       they've spent at least a certain amount to buy [ they've spent
       {{number_format($contract->conditionFulfilled)}} so far ]
    @endif
    <div class='ms-3'>You have {{number_format($quantity)}} {{$itemName}}</div>
    <div class='ms-3'>
      @if ($contract->userID == $userID)
        <button id='cancelContract-{{$contract->id}}'
          class='cancelContract btn btn-warning'>cancel</button>
      @else
        @if ($quantity >=  1)
          <button id='sellToBuyContract-{{$contract->id}}-1'
            class='sellToBuyContract btn btn-success'>sell x1 (+{{number_format($contract->price)}} clacks)</button>
        @endif
        @if ($quantity >=  10)
          <button id='sellToBuyContract-{{$contract->id}}-10'
            class='sellToBuyContract btn btn-success'>sell x10 (+{{$contract->price * 10}} clacks)</button>
        @endif
        @if ($quantity >=  100)
          <button id='sellToBuyContract-{{$contract->id}}-100'
            class='sellToBuyContract btn btn-success'>sell x100 (+{{$contract->price * 100}} clacks)</button>
        @endif
        @if ($quantity >=  1000)
          <button id='sellToBuyContract-{{$contract->id}}-1000'
            class='sellToBuyContract btn btn-success'>sell x1,000 (+{{$contract->price * 1000}} clacks)</button>
        @endif
      @endif
    </div>



  @elseif ($contract->category == 'freelance')
    {{$username}}  available to <span class='fw-bold'>freelance</span> {{$contract->action}}
     for {{number_format($contract->price)}} clack(s) until
    @if ($contract->until == 'workHours')
       there are no more work hours available.
    @elseif ($contract->until == 'food')
       food runs out.
    @elseif ($contract->until == 'finite')
       they've freelanced {{number_format($contract->condition)}} time(s) [{{number_format($contract->conditionFulfilled)}} times so far ]
    @endif
    @if ($contract->minSkillLevel != null)
       they have a skill level of {{$contract->minSkillLevel}}
    @endif
    <div class='ms-3'>
      @if ($contract->userID == $userID)
        <button id='cancelContract-{{$contract->id}}'
          class='cancelContract btn btn-warning'>cancel</button>
      @elseif ($clacks >= $contract->price)
        @if($contract->action == 'build')
          <select id='contractBuildableBuildings-{{$contract->id}}-market' @if (count($buildableBuildings) < 1) disabled @endif>
            <option></option>
            @foreach($buildableBuildings as $building)
              <option value='{{$building}}'>{{$building}}</option>
            @endforeach
          </select>
          <button id='freelanceBuild-{{$contract->id}}-market'
            class='freelanceBuild btn btn-danger' @if (count($buildableBuildings) < 1) disabled @endif>
            {{$contract->action}} (-{{$contract->price}})
          </button>
        @elseif($contract->action == 'repair')
          <select id='contractRepairableBuildings-{{$contract->id}}-market' @if (count($repairableBuildings) < 1) disabled @endif>
            <option></option>
            @foreach($repairableBuildings as $building)
              <option value='{{$building->id}}'>{{$building->name}} ({{$building->uses / $building->totalUses * 100}}%)</option>
            @endforeach
          </select>
          <button id='freelanceRepair-{{$contract->id}}-market'
            class='freelanceRepair btn btn-danger' @if (count($repairableBuildings) < 1) disabled @endif>
            {{$contract->action}} (-{{$contract->price}})
          </button>
        @else
          <button id='freelance-{{$contract->id}}-market'
            class='freelance btn btn-danger'>
            {{implode(' ', explode('-', $contract->action))}}
          </button>
        @endif
      @endif
    </div>

  @elseif ($contract->category == 'hire')
   {{$username}} <span class='fw-bold'>hiring</span> anyone to <span class='fw-bold'>
     {{implode(' ', explode('-', $contract->action))}}</span> for
     {{number_format($contract->price)}} clack(s) until
   @if ($contract->until == 'gone')
      money runs out.
   @elseif ($contract->until == 'finite')
      this action has been done {{number_format($contract->condition)}}
       time(s) [ done {{number_format($contract->conditionFulfilled)}} time(s) ]
   @endif
   (required minimum skill level: {{$contract->minSkillLevel}})
   <div class='ms-3'>
   @if ($contract->userID == $userID)
     <button id='cancelContract-{{$contract->id}}'
       class='cancelContract btn btn-warning'>cancel</button>
   @elseif (in_array($contract->action, $unlocked))
       <button id='hire-{{$contract->id}}-market' class='hire btn btn-success'>
         {{implode(' ', explode('-', $contract->action))}}
       </button>
   @endif
  </div>

   @elseif ($contract->category == 'lease')
     {{$username}} <span class='fw-bold'>leasing</span> {{$contract->landType}} at
     {{number_format($contract->price)}} clack(s) per use
     <div class='ms-3'>
     @if ($contract->userID == $userID)
       <button id='cancelContract-{{$contract->id}}'
         class='cancelContract btn btn-warning'>cancel</button>
     @elseif (\App\Lease::areTheyAlreadyLeasing($contract->landType, $userID)){
       <button id='cancelLease-{{$contract->id}}'
         class='cancelLease btn btn-warning ms-3'>
         cancel lease
       </button>
         You are currently leasing this land.
     @elseif ($clacks >= $contract->price)
       <button id='lease-{{$contract->id}}' class='lease btn btn-danger'>
         accept lease
       </button>
     @endif
    </div>


  @elseif ($contract->category == 'leaseBuilding')
   {{$username}} <span class='fw-bold'>leasing building</span>
   (<span class='text-decoration-underline'>
     {{$contract->buildingName}}
   </span>)
   for {{number_format($contract->price)}} clack(s)
   <div class='ms-3'>
   @if ($contract->userID == $userID)
     <button id='cancelContract-{{$contract->id}}'
       class='cancelContract btn btn-warning'>cancel</button>
   @elseif (\App\BuildingLease
    ::areTheyLeasingThis($contract->buildingName, $userID)){
     <button id='cancelBuildingLease-{{$contract->id}}'
       class='cancelBuildingLease btn btn-warning ms-3'>
       cancel building lease
     </button>
     You are currently leasing a {{$contract->buildingName}}
   @elseif ($clacks >= $contract->price)
     <button id='leaseBuilding-{{$contract->id}}'
       class='leaseBuilding btn btn-danger'>
       lease {{$contract->buildingName}}
     </button>
   @endif
  </div>

  @elseif ($contract->category == 'reproduction')
    <div>
      {{$username}}  paying anyone {{number_format($contract->price)}}
      clack(s) to <span class='fw-bold'>create and raise a child</span> for
      them (you will <span class='fw-bold text-danger'>Rebirth</span> but will be
      paid your fee after [to avoid the estate tax])
    </div><div class='ms-3'>
      @if ($contract->userID == $userID)
        <button id='cancelContract-{{$contract->id}}'
          class='cancelContract btn btn-warning'>cancel</button>
      @else
        <button id='reproduction-{{$contract->id}}'
          class='reproduction btn btn-success'>reproduce</button>
      @endif
    </div>

  @elseif ($contract->category == 'sellLand')
    {{$username}} <span class='fw-bold'>selling</span>
    <button id='goToLand-{{$contract->landID}}'
      class='goToLand btn btn-link'>
      parcel #{{$contract->landID}}
    </button> ({{\App\Land::find($contract->landID)->type}}) for
      {{number_format($contract->price)}} clack(s)
    <div class='ms-3'>
      @if ($contract->userID == $userID)
        <button id='cancelContract-{{$contract->id}}'
          class='cancelContract btn btn-warning'>cancel</button>
      @elseif ($clacks >= $contract->price)
          <button id='sellLand-{{$contract->id}}'
            class='sellLand btn btn-danger'>buy</button>
      @endif
    </div>



  @elseif ($contract->category == 'sellOrder')
    {{$username}} <span class='fw-bold'>selling</span>
    <span class='text-decoration-underline'>{{$itemName}}</span> for
      {{number_format($contract->price)}} clack(s) each until
    @if ($contract->until == 'gone')
      {{$itemName}} runs out
    @elseif ($contract->until == 'sold')
       they've sold {{number_format($contract->condition)}} {{$itemName}}
       [ sold {{number_format($contract->conditionFulfilled)}}  so far ]
    @elseif ($contract->until == 'earn')
      they earn a certain amount of money
    @endif
    <div class='ms-3'>
      @if ($contract->userID == $userID)
        <button id='cancelContract-{{$contract->id}}'
          class='cancelContract btn btn-warning'>cancel</button>
      @else
        @if($clacks >= $contract->price)
          <button id='buyFromSellContract-{{$contract->id}}-1'
            class='buyFromSellContract btn btn-danger'>buy x1</button>
        @endif
        @if ($clacks >= $contract->price * 10 )
          <button id='buyFromSellContract-{{$contract->id}}-10'
            class='buyFromSellContract btn btn-danger'>buy x10</button>
        @endif
        @if ($clacks >= $contract->price * 100  )
          <button id='buyFromSellContract-{{$contract->id}}-100'
            class='buyFromSellContract btn btn-danger'>buy x100</button>
        @endif
        @if ($clacks >= $contract->price * 1000 )
          <button id='buyFromSellContract-{{$contract->id}}-1000'
            class='buyFromSellContract btn btn-danger'>buy x1,000</button>
        @endif
      @endif
    </div>


  @endif
</div>
@endforeach
</div>
