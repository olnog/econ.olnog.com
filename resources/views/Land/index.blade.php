<div class='text-center m-3'>
  <button id='show-moreLand' class='show btn btn-link'> [ MORE ] </button>
  <button id='hide-moreLand' class='hide btn btn-link d-none'> [ LESS ] </button>
</div>
<div id='moreLand' class='d-none'>
<div class='text-center m-1'>
  In order to get land you must use the Exploration skill to explore and find
  new land, buy it from other players or launch a hostile takeover.
</div><div class='text-center m-1'>
  The State requires bribes from each land owner in order to keep it protected.
  Land owners who pay less than the average bribe will be unprotected and
  other players may attempt to launch a hostile takeover by bidding 2x the current valuation against that
  property.
</div><div class='text-center payAllBribes'>
  Pay Bribe On All Your Properties:
</div><div class='text-center payAllBribes'>

  <button id='payAllBribes-1' class='payAllBribes btn btn-danger'>+1</button>
  <button id='payAllBribes-10' class='payAllBribes btn btn-danger'>+10</button>
  <button id='payAllBribes-100' class='payAllBribes btn btn-danger'>+100</button>
</div><div class='m-1 text-center autoBribes'>
  Auto-Bribe:
  <input type='number' id='autoBribe' value='0'>
  <button id='setAutoBribe' class='btn btn-primary '>set</button>
</div><div class='mb-3'>
  This is the amount that you will automatically pay when bribes are checked to see if you are protected.
</div>
<div class='m-1 text-center payAllBribes'>
  The average bribe now is <span id='avgBribe'></span> clack(s).

</div>
</div>


<div class='row'>
    <span class='fw-bold me-3'>Filter:</span>
</div><div class='row'>
  <div class='col'>

    <input type='radio'  name='landFilter' class='landFilter' value='all' checked> All
  </div><div class='col'>

    <input type='radio' name='landFilter' class='landFilter ' value='yours'> Your Land?
  </div><div class='col'>

    <input type='radio' name='landFilter' class='landFilter ' value='takeovers'> Hostile Takeovers?
  </div>
</div><div class='row'>
  <div class='col text-center'>
    Owner:
    <input type='text' id='landOwnerFilter' class='landFilter'>

    <button id='clearLandOwnerFilter' class='btn btn-outline-danger'>x</button>
  </div>
</div><div class='row'>
  <div class='col'>
    <span>
      Show Only:
    </span>
      <select name='landTypeFilter' id='landTypeFilter' class='landFetch'>
        <option value='jungle' @if ($landType == 'jungle') selected @endif>Jungle</option>
        <option value='forest' @if ($landType == 'forest') selected @endif>Forest</option>
        <option value='mountains' @if ($landType == 'mountains') selected @endif >Mountains</option>
        <option value='plains' @if ($landType == 'plains') selected @endif>Plains</option>
        <option value='desert' @if ($landType == 'desert') selected @endif>Desert</option>
        </select>
    </div><div class='col'>
      Sort By:
      <select id='landSortByFilter' class='landFetch'>
        <option value='null' @if ($sort == null) selected @endif>Parcel #</option>
        <option value='valuation'@if ($sort == 'valuation') selected @endif >Value</option>
        <option value='name' @if ($sort == 'name') selected @endif>Owner</option>
      </select>
    </div>
  </div>
<div id='landTable'>


@foreach ($land as $parcel)
  <?php
  $landForSale = \App\Land::aretheySellingThis($parcel->id);
  $ownerClass = '';
  $bribeButtons = '';
  $takeoverButton = '';
  $takeoverClass = '';
  $protectedCaption = $parcel['protected'] ? "protected" : "unprotected";
  if (!$parcel['protected'] && $parcel['hostileTakeoverBy']> 0){
    $takeoverClass = ' takeovers ';
    $takeoverButton = "<div><a href='/land/" . $parcel['id'] . "'>hostile takeover</a></div><div>By <span class='fw-bold'>";
    $takeoverButton .= \App\User::find($parcel['hostileTakeoverBy'])->name;

  $takeoverButton .= "</span></div>";

  } else if (!$parcel['protected'] && $parcel['userID'] != \Auth::id()){
    $takeoverButton = "<button id='takeover-" . $parcel['id'] . "-"
      . $parcel['valuation'] . "' class='takeover btn btn-primary'>hostile takeover</button>";
  }
  if ($parcel['userID'] == \Auth::id()){
    $ownerClass = 'ownedLand fw-bold';

    $bribeButtons = "<div class='ms-3'>Current Bribe: " . $parcel->bribe . "</div><div class='ms-3'> <button id='payBribe-" . $parcel['id']
      . "-1' class='payBribe btn btn-danger'>+1</button><button id='payBribe-"
      .  $parcel['id'] . "-10' class='payBribe btn btn-danger'>+10</button><button id='payBribe-"
      .  $parcel['id'] . "-100' class='payBribe btn btn-danger'>+100</button></div>";
  }
  ?>

    <div id='parcel{{$parcel['id']}}' class=' p-3 mt-3 {{$ownerClass}}
      {{$takeoverClass}} {{$parcel->type}} " parcel ownedBy{{$parcel->name}}'>
      <div>
      Parcel #{{$parcel['id']}} - Type: {{$parcel->type}} -
      Value: {{ number_format($parcel->valuation) }}
      <a href='/contracts/create?category=buyLand&parcelType={{$parcel->type}}' class='btn ms-3 createContract'>
        <img src='/img/icons8-buy-24.png'>
      </a>
      @if ($parcel->userID == \Auth::id())
      <a href='/contracts/create?category=sellLand&parcelID={{$parcel->id}}' class='btn ms-3 createContract'>
        <img src='/img/icons8-sell-24.png'>
      </a>
      @endif
      <?php $buyLandContract = \App\Contracts::fetchHighestBuyLandContract($parcel->type); ?>
      @if ($buyLandContract != null && $parcel->userID == \Auth::id())
        <button id='buyLand-{{$buyLandContract->id}}-land'
          class='buyLand btn btn-success'>sell
          (+{{number_format($buyLandContract->price)}} clacks)</button>
      @endif
      @if ($landForSale != null)
        <button id='sellLand-{{$landForSale->id}}' class='sellLand btn btn-danger'>
          buy (-{{$lndForSale->price}} clacks)
        </button>
      @endif
      </div><div class='ms-3'>
        Oil: {{number_format($parcel->oil)}}

        @if ($parcel->type == 'mountains')
          Coal: {{number_format($parcel->coal)}} Copper: {{number_format($parcel->copper)}}
          Iron Ore: {{number_format($parcel->iron)}} Stone: {{number_format($parcel->stone)}}
          Uranium: {{number_format($parcel->uranium)}}
        @elseif ($parcel->type == 'desert')
          Sand: {{number_format($parcel->sand)}}
        @elseif ($parcel->type == 'forest')
          Trees: {{number_format($parcel->logs)}}
        @endif
      </div><div class='ms-3'>
        Owner: <a class='filterByOwner' href='#'>{{ $parcel->name }}</a> ({{$protectedCaption}})
      </div><div class='ms-3 pt-3'>
         {!! $takeoverButton !!}

      </div><div class=''>
        {!! $bribeButtons !!}
      </div>
    </div>
  @endforeach
</div>
