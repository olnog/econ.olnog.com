@extends('layouts.app')

@section('content')
<div class='mb-5'>
  <a href='{{ route('home') }}'>back</a>
</div>
<div>
  What type of contract do you want to create?
</div><div class='ms-3 mb-3'>
  <input type='radio' class='contractCategory' name='category' value='hire'> Hire</input>
  <input type='radio' class='contractCategory' name='category'
    value='freelance' @if ($defaultCategory == 'freelance') checked @endif> Freelance</input>

  <input type='radio' class='contractCategory' name='category' value='construction'>Available for Construction & Repair</input>
  <input type='radio' class='contractCategory' name='category' value='buyOrder'> Buy Items</input>
  <input type='radio' class='contractCategory' name='category' value='sellOrder'> Sell Items</input>
  <input type='radio' class='contractCategory' name='category' value='buyLand' @if ($defaultCategory == 'buyLand') checked @endif>Buy Land</input>
  <input type='radio' class='contractCategory' name='category' value='sellLand' @if ($defaultCategory == 'sellLand') checked @endif>Sell Land</input>
  <input type='radio' class='contractCategory' name='category' value='reproduction'>Reproduction</input>
  <input type='radio' class='contractCategory' name='category' value='lease'>Lease Land</input>
  <input type='radio' class='contractCategory' name='category' value='leaseBuilding'>Lease Buildings</input>


</div><div id='contractError' class='text-danger text-center'>

</div><div id='leaseBuildingSection' class='ms-3 contractSection mb-5 d-none '>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='leaseBuilding' >
    <div>
      You want <input type='number' name='price' value='1'> clack(s) each time someone uses your
      <select name='buildingID'>
        <option></option>
        @foreach($buildings as $building)
          <option value='{{$building->id}}'>{{$building->name}}</option>
        @endforeach

      </select>
    </div>
    <button class='btn btn-primary form-select mt-5'>create contract</button>
  </form>
</div><div id='leaseSection' class='ms-3 contractSection mb-5 d-none '>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='lease' >
    <div>
      You want <input type='number' name='price' value='1'> clack(s) each time someone uses your
      <select name='landType'>
        <option value='desert'>desert</option>
        <option value='forest'>forest</option>
        <option value='jungle'>jungle</option>
        <option value='mountains'>mountains</option>
        <option value='plains'>plains</option>
      </select>
    </div>
    <button class='btn btn-primary form-select mt-5'>create contract</button>
  </form>
</div><div id='reproductionSection' class='ms-3 contractSection mb-5 d-none '>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='reproduction' >
    <div>
      10% of your current starting work hours ({{ceil($labor->startingWorkHours * .1) }}) will be deducted from both <span class='fw-bold'>you and the contractor</span>. You and the contractor will both receive a child. Each child you own adds one more food upkeep requirement to each action you do. If you run out of food, your children will die.
    </div><div>
      Children can be redeemed at Rebirth as a Legacy perk so that you will start the next round with the same level of skill in one skill of your choice.
    </div><div>
      You are willing to pay <input type='number' name='price' value='1'> clack(s).
    </div>
    <button class='btn btn-primary form-select mt-5'>create contract</button>
  </form>


</div><div id='sellLandSection' class='ms-3 contractSection mb-5 @if ($defaultCategory != 'sellLand') d-none @endif'>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='sellLand' >
    Want <input type='number' name='price' value='1'> clack(s) for
    <select name='landID'>
      <option></option>

      @foreach($land as $parcel)
        <option value='{{$parcel->id}}'> Parcel #{{$parcel->id}} - {{$parcel->type}}</option>
      @endforeach
    </select>
    <button class='btn btn-primary form-select mt-5'>create contract</button>
  </form>
</div><div id='buyLandSection' class='ms-3 contractSection @if ($defaultCategory != 'sellLand') d-none @endif'>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='buyLand' >
    <div class='mt-5'>
      Willing to pay <input type='number' name='price' value='1'> clack(s)
      to buy
      <select name='landType'>
        <option></option>
        <option>forest</option>
        <option>mountains</option>
        <option>plains</option>
        <option>jungle</option>

      </select>
    </div><div>
      Until:
    </div><div>
      <input type='radio' name='until' value='gone'> You run out of money
    </div><div>
      <input type='radio' name='until' value='finite' checked> You buy <input type='number' value='1' name='condition'> pieces of land.
    </div><div class='mt-5'>
      <button class='btn btn-primary form-control'>create contract</button>
    </div>
  </form>
</div><div id='freelanceSection' class='ms-3 contractSection @if ($defaultCategory != 'freelance') d-none @endif'>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='freelance' >
    <div>
      Available to freelance at <input type='number' name='price' value='1'> clack(s) per action
    </div><div>
      Freelance Action:
      <select name='action'><option></option>

        @foreach($freelanceActions as $action)
          @if (!in_array($action->name, \App\Robot::fetchBannedActions()))
            <option>{{ $action->name }}</option>
          @endif
        @endforeach

      </select>
    </div><div>
      Until:
    </div><div>
      <input type='radio' name='until' value='workHours'> You run out of work hours.
    </div><div>
      <input type='radio' name='until' value='food'> You run out of food. (Otherwise, you will keep going without food at 2h per action instead of 1h.)
    </div><div>
      <input type='radio' name='until' value='finite' checked> You do the action <input type='number' name='condition' value='1'> times.
    </div><div>
      <button class='btn btn-primary form-control'>create contract</button>
    </div>
  </form>
</div><div id='hireSection' class='ms-3 contractSection d-none'>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='hire' >

  <div>

    Hiring at
    <input type='number' name='price' value='1'>
    clack(s)
    <input type='radio' name='whichPrice' value='price' checked>
     per action OR
    <input type='radio' name='whichPrice' value='pricePerSkill'>
    per skill level
  </div><div>
    Action:
    <select name='action'> <option></option>
      @foreach($hireableActions as $action)
        @if (!in_array($action->name, \App\Robot::fetchBannedActions()))
          <option value='{{ $action }}'>{{ $action->name }}</option>
        @endif
      @endforeach
    </select>
  </div><div>
    Min Skill Level: <select name='minSkillLevel'>
    @for($i = 1; $i < 6; $i++)
      <option value='{{ $i }}'>{{ $i }}</option>
    @endfor
    </select>
  </div><div>
    Until:
  </div><div>
    <input type='radio' name='until' value='gone'> Money Runs Out
  </div><div>
    <input type='radio' name='until' value='finite' checked> Action Is Done
    <input type='number' value='1' name='condition'>  Times
  </div><div class='mt-5'>
    <button class='form-select btn-primary'>create contract</button>
  </div>

</form>
</div><div id='repairSection' class='ms-3 contractSection d-none'>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='repair' >

    <div>
      Willing to pay <input type='number' name='price'> clacks
    </div><div>
      Building To Be Repaired:
      <select name='buildingID'>
        <option></option>
          @foreach ($buildings as $building)
            <option value='{{ $building->id }}'>{{ $building->name }}</option>
          @endforeach
      </select>
    </div><div>
      Minimum Construction Skill Level Required:
      <select name='minSkillLevel'>
        @for($i = 1; $i < 6; $i++)
          <option value='{{ $i }}'> {{ $i }}</option>
        @endfor
      </select>
    </div><div>
      Repair If:
      <input type='radio' name='repairIf' value='Dead' class='me-1 ms-2' checked>At 0%
      <input type='radio' name='repairIf' value='Less' class='me-1 ms-2'>Less than 100%
    </div><div class=''>
      Until:
    </div><div>
      <input type='radio' name='until' value='gone'> Money runs out
    </div><div>
      <input type='radio' name='until' value='finite' checked>
      It's been repaired this many times.
      <input type='number' name='condition' value='1'>

    </div><div class='mt-5'>
      <button class='form-select btn-primary'>create contract</button>
    </div>
  </form>
</div><div id='constructionSection' class='ms-3 contractSection d-none'>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='construction' >

    <div>
      You have a Construction skill of {{$constructionSkill->rank}}. You will use your work hours to build and repair buildings for other players using their resources.
    </div><div>
      How much do you want to charge to repair and build?
      <input type='number' name='price' value='2'> clacks each time
    </div><div class='mt-5'>
      <button class='form-select btn-primary'>create contract</button>
    </div>
  </form>
</div><div id='buyOrderSection' class='ms-3 contractSection d-none'>
  <div>
    <form method='POST' action={{ route('contracts.store')}}>
      @csrf()
      <input type='hidden' name='category' value='buyOrder' >
      Buying: <select name='itemTypeID'>
      <option></option>
      @foreach($itemTypes as $itemType)
        <option value='{{$itemType->id}}'>{{ $itemType->name }}
          @if ($itemType->material != null)
            ({{ $itemType->material }} / {{ $itemType->durability }})
          @endif
        </option>
      @endforeach
      </select>
      for <input type='number' name='price' > clacks each
    </div><div class='mt-3'>
      Until:
    </div><div class='ms-3'>
      <input type='radio' class='buyUntil' name='until' value='gone' checked> I run out of money or space (This always stop the contract when this happens.)
    </div><div class='ms-3'>
      <input type='radio' class='buyUntil' name='until' value='bought'> I have bought [ amount ] of this item.
    </div><div class='ms-3'>
      <input type='radio' class='buyUntil' name='until' value='inventory'> I have [ amount ] of this item.

    </div><div id='buyCondition' class='mt-3 d-none'>
      Amount: <input type='number' name='condition' >
    </div><div class='mt-5'>
      <button id='createContract' class='btn btn-primary form-control' >Create Contract</button>
    </div>
  </form>
</div><div id='sellOrderSection' class='ms-3 contractSection d-none'>
  <div>
    <form method='POST' action={{ route('contracts.store')}}>
      @csrf()
      <input type='hidden' name='category' value='sellOrder' >
      Selling: <select name='itemTypeID'>
      <option></option>
      @foreach($items as $item)
        @if ($item->name != 'Nuclear Waste')
        <option value='{{$item->itemTypeID}}'>
          {{$item->quantity}}
          {{ $item->name }}
          @if ($item->material != null)
            ({{ $item->material }} / {{ $item->durability }})
          @endif
        </option>
        @endif
      @endforeach
      </select>
      for <input type='number' name='price' > clacks each
    </div><div class='mt-3'>
      Until:
    </div><div class='ms-3'>
      <input type='radio' class='sellUntil' name='until' value='gone' checked> I run out items (This always stop the contract when this happens.)
    </div><div class='ms-3'>
      <input type='radio' class='sellUntil' name='until' value='sold'> I have sold [ amount ] of this item.

    </div><div id='sellCondition' class='mt-3 d-none'>
      Amount: <input type='number' name='condition' >
    </div><div class='mt-5'>
      <button id='createContract' class='btn btn-primary form-control' >Create Contract</button>
    </div>
  </form>
</div>

@endsection
