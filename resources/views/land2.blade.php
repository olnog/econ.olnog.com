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
        <button id='buyLand-"{{$buyLandContract->id}}"'
          class='buyLand btn btn-success'>sell
          (+{{number_format($buyLandContract->price)}} clacks)</button>
      @endif
      @if ($landForSale != null)
        <button id='sellLand-{{$landForSale->id}}' class='sellLand btn btn-danger'>
          buy (-{{$landForSale->price}} clacks)
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
