@extends('layouts.app')

@section('content')
<div class='text-center'>
  <a href='{{ route('home') }}'>[ home ]</a>
</div>
<div>
  What type of contract do you want to create?
</div><div class='ms-3 mb-3'>
  <div class='fw-bold'>Labor</div>
  <input type='radio' class='contractCategory ms-3 me-1' name='category' value='hire'> Hire</input>
  <input type='radio' class='contractCategory ms-3 me-1' name='category'
    value='freelance' @if ($defaultCategory == 'freelance') checked @endif> Freelance</input>
  <!--<input type='radio' class='contractCategory ms-3 me-1' name='category' value='construction'>Construction & Repair</input>-->
  <input type='radio' class='contractCategory ms-3 me-1' name='category' value='reproduction'>Reproduction</input>


  <div class='fw-bold'>Items</div>
  <input type='radio' class='contractCategory ms-3 me-1' name='category'
    value='buyOrder' @if ($defaultCategory == 'buyOrder') checked @endif>
    Buy Items</input>
  <input type='radio' class='contractCategory ms-3 me-1' name='category'
    value='sellOrder' @if ($defaultCategory == 'sellOrder') checked @endif>
    Sell Items</input>

  <div class='fw-bold'>Land</div>
  <input type='radio' class='contractCategory ms-3 me-1' name='category' value='buyLand' @if ($defaultCategory == 'buyLand') checked @endif>Buy Land</input>
  <input type='radio' class='contractCategory ms-3 me-1' name='category' value='sellLand' @if ($defaultCategory == 'sellLand') checked @endif>Sell Land</input>
  <input type='radio' class='contractCategory ms-3 me-1' name='category' value='lease'>Lease Land</input>

  <div class='fw-bold'>Buildings</div>
  <input type='radio' class='contractCategory ms-3 me-1' name='category'
    value='leaseBuilding'
    @if ($defaultCategory == 'leaseBuilding') checked @endif>Lease Buildings</input>


</div><div id='contractError' class='text-danger text-center'>

</div><div id='leaseBuildingSection' class='ms-3 contractSection mb-5 @if ($defaultCategory != 'leaseBuilding') d-none @endif '>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='leaseBuilding' >
    <div>
      You want <input type='number' name='price' value='1'> clack(s) each time someone uses your
      <select name='buildingID'>
        <option></option>
        @foreach($buildings as $building)
          <option value='{{$building->id}}' @if($buildingID == $building->id) selected @endif )>{{$building->name}}</option>
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
    <div class='mb-1'>
      Pay someone to create a Child for you. They will receive their payment after their Rebirth.
    </div><div class='mb-1'>
      <span class='fw-bold'>Children</span> can be used during Rebirth to preserve your rank progress on your actions.
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
        <option value='{{$parcel->id}}' @if ($parcelID == $parcel->id) selected @endif > Parcel #{{$parcel->id}} - {{$parcel->type}}</option>
      @endforeach
    </select>
    <button class='btn btn-primary form-select mt-5'>create contract</button>
  </form>


</div><div id='buyLandSection' class='ms-3 contractSection @if ($defaultCategory != 'buyLand') d-none @endif'>
  <form method='POST' action={{ route('contracts.store')}}>
    @csrf()
    <input type='hidden' name='category' value='buyLand' >
    <div class='mt-5'>
      Willing to pay <input type='number' name='price' value='1'> clack(s)
      to buy
      <select name='landType'>
        <option></option>
        <option @if($parcelType == 'forest') selected @endif >forest</option>
        <option @if($parcelType == 'mountains') selected @endif >mountains</option>
        <option @if($parcelType == 'plains') selected @endif >plains</option>
        <option @if($parcelType == 'jungle') selected @endif >jungle</option>
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
      <select name='action'>
        <option></option>
        @foreach($freelanceActions as $action)
          <?php
            $lowestFreelance = \App\Contracts::fetchLowestFreelance($action);
          ?>
          @if (($action == 'build' || $action=='repair') || !in_array($action, $banned))
            <option value='{{ $action }}'>
              {{ $action }}
              @if ($lowestFreelance != null)
                [Market: {{$lowestFreelance->price}}]
              @endif
            </option>
          @endif
        @endforeach
      </select>
    </div><div>
      Until:
    </div><div>
      <input type='radio' name='until' value='workHours'> Indefinitely
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
     per action
  </div><div>
    Action:
    <select name='action'>
      <option></option>
      @foreach($hireableActions as $action)
        @if (!in_array($action->name, $banned))
          <option value='{{ $action->name }}'>{{ $action->name }}</option>
        @endif
      @endforeach
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


</div><div id='buyOrderSection' class='ms-3 contractSection
  @if ($defaultCategory != 'buyOrder') d-none @endif'>
  <div>
    <form method='POST' action={{ route('contracts.store')}}>
      @csrf()
      <input type='hidden' name='category' value='buyOrder' >
      Buying: <select name='itemTypeID'>
      <option></option>
      @foreach($itemTypes as $itemType)
        <?php
          $highestBuy = \App\Contracts::fetchHighestBuy($itemType->id);
        ?>
        <option value='{{$itemType->id}}'
          @if($itemTypeID = $itemType->id) selected @endif >{{ $itemType->name }}
          @if ($highestBuy != null)
            [Market: {{$highestBuy->price}}]
          @endif
        </option>
      @endforeach
      </select>
      for <input type='number' name='price' min=.01 step=.01 value=''> clacks each
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



</div><div id='sellOrderSection' class='ms-3 contractSection
  @if ($defaultCategory != 'sellOrder') d-none @endif'>
  <div>
    <form method='POST' action={{ route('contracts.store')}}>
      @csrf()
      <input type='hidden' name='category' value='sellOrder' >
      Selling: <select name='itemTypeID'>
      @foreach($items as $item)
        <?php
          $lowestSell = \App\Contracts::fetchLowestSell($item->itemTypeID);
        ?>
        <option value='{{$item->itemTypeID}}' @if($itemID == $item->itemTypeID) selected @endif >
          {{number_format($item->quantity)}}
          {{ $item->name }}
          @if ($lowestSell != null)
            [Market: {{$lowestSell->price}}]
          @endif
        </option>
      @endforeach
      </select>
      for <input type='number' name='price' min=.01 step=.01> clacks each
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
