

<span class='fw-bold'>your items:</span>
 <button id='show-itemSection' class='show btn btn-link d-none'>[ + ]</button>
 <button id='hide-itemSection' class='hide btn btn-link '>[ - ]</button>
<div id='itemSection'>
<div>
  <input type='checkbox' id='showOnlyInventory' checked> Show Only Items In Inventory?
</div><div>
  <input type='checkbox' id='showDump'> Dump Items?(gone forever)

</div><div id='itemListings' class='ms-3 pb-3'>
@foreach ($items as $item)
  <?php
    $buyContract = \App\Contracts::fetchHighestBuy($item->itemTypeID);
    $sellContract = \App\Contracts::fetchHighestSell($item->itemTypeID);
   ?>
  <div class='mt-3 @if ($item->quantity < 1)  noQuantity d-none @endif'>
    <div class='row'>
      <div class='col-lg-3 col'>
        @if($buyContract != null || $sellContract != null)
          <button id='show-buyingAndSelling{{$item->id}}'
            class='show btn btn-link me-3'>+</button>
          <button id='hide-buyingAndSelling{{$item->id}}'
            class='hide btn btn-link d-none me-3'>-</button>
        @endif
        {{$item->name}}: {{number_format($item->quantity)}}

      </div><div class='col-lg-2 col'>
        @if ($item->name == 'Books')
          <button id='readBook'
            class=' btn btn-warning'
              @if ($item->quantity < $booksReq) disabled @endif>
            Read {{$booksReq}} Books
          </button>
        @elseif (in_array($item->name, $equippableItems))
          <button id='equipItem-{{$item->id}}'
            class='equipItem btn btn-info'
              @if($item->quantity <1) disabled @endif>
            equip
          </button>
        @elseif ($item->name == 'Robots')
          <button id='programRobot'class='btn btn-link ms-3'>
            [ program & activative ]
          </button>
          <select id='robotActionList'>
            <option></option>
            @foreach($actions as $action)
              <option option='{{$action}}'>{{str_replace('-', ' ', $action)}}
            @endforeach
          </select>
        @endif
      </div><div class='col'>
        <a href='/contracts/create?category=buyOrder&itemID={{$item->itemTypeID}}'
          class='btn createContract'>
          <img src='/img/icons8-buy-24.png'>
        </a>
        @if ($item->quantity > 0)
        <a href='/contracts/create?category=sellOrder&itemID={{$item->itemTypeID}}'
          class='btn createContract'>
          <img src='/img/icons8-sell-24.png'>
        </a>
        @endif

      </div>
    </div><div>
      @if ($item->quantity > 0)
        <button id='dump-{{$item->id}}-1' class='btn btn-danger m-2 d-none dump'>
          dump 1x
        </button>
      @endif
      @if ($item->quantity >= 10)
        <button id='dump-{{$item->id}}-10' class='btn btn-danger m-2 d-none dump'>
          dump 10x
        </button>
      @endif
      @if ($item->quantity >= 100)
        <button id='dump-{{$item->id}}-100' class='btn btn-danger m-2 d-none dump'>
          dump 100x
        </button>
      @endif
      @if ($item->quantity >= 1000)
        <button id='dump-{{$item->id}}-1000' class='btn btn-danger m-2 d-none dump'>
          dump 1,000x
        </button>
      @endif
    </div><div id='buyingAndSelling{{$item->id}}' class='d-none'>
      @if ($buyContract != null)
        <button id='sellToBuyOrder-{{$buyContract->id}}-1'
          class='sellToBuyOrder btn btn-success m-2
          @if ($item->quantity < 1) disabled @endif '>
          sell 1x
          (<span class='fp'>+{{number_format($buyContract->price)}} clacks</span>)
        </button>
        <button id='sellToBuyOrder-{{$buyContract->id}}-10'
          class='sellToBuyOrder btn btn-success m-2
          @if ($item->quantity < 10) disabled @endif '>
          sell 10x
          (<span class='fp'>+{{number_format($buyContract->price * 10)}} clacks</span>)
        </button>
        <button id='sellToBuyOrder-{{$buyContract->id}}-100'
          class='sellToBuyOrder btn btn-success m-2
          @if ($item->quantity < 100) disabled @endif '>
          sell 100x
          (<span class='fp'>+{{number_format($buyContract->price * 100)}} clacks</span>)
        </button>
        <button id='sellToBuyOrder-{{$buyContract->id}}-1000'
          class='sellToBuyOrder btn btn-success m-2
          @if ($item->quantity < 1000) disabled @endif '>
          sell 1,000x
          (<span class='fp'>+{{number_format($buyContract->price * 1000)}} clacks</span>)
        </button>
      @endif
      @if ($sellContract != null)
        <button id='buyFromSellOrder-{{$sellContract->id}}-1'
          class='buyFromSellOrder btn btn-danger m-2'
          @if ($clacks < $sellContract->price) disabled @endif>
          buy 1x
          (-{{$sellContract->price}} clacks)
        </button>
        <button id='buyFromSellOrder-{{$sellContract->id}}-10'
          class='buyFromSellOrder btn btn-danger m-2'
          @if ($clacks < $sellContract->price * 10) disabled @endif>
          buy 10x
          (-{{$sellContract->price * 10}} clacks)
        </button>
        <button id='buyFromSellOrder-{{$sellContract->id}}-100'
          class='buyFromSellOrder btn btn-danger m-2'
          @if ($clacks < $sellContract->price * 100) disabled @endif>
          buy 100x
          (-{{$sellContract->price * 100}} clacks)
        </button>
        <button id='buyFromSellOrder-{{$sellContract->id}}-1000'
          class='buyFromSellOrder btn btn-danger m-2'
          @if ($clacks < $sellContract->price * 1000) disabled @endif>
          buy 1,000x
          (-{{$sellContract->price * 1000}} clacks)
        </button>
      @endif

    </div></div>
@endforeach
</div>
</div><div>
      <span class='fw-bold'>state </span>
      <button id='show-stateSection' class='show btn btn-link d-none'>[ + ]</button>
      <button id='hide-stateSection' class='hide btn btn-link'>[ - ]</button>
    </div><div id='stateSection'>
      <div>
        <span class='fw-bold'>Sort By:</span>
        <input type='radio' id='stateSort'  name='stateSort' value='name' checked> Name
        <input type='radio' id='stateSort' name='stateSort' value='cost'> Price
        <input type='radio' id='stateSort' name='stateSort' value='unitCost'> Unit Price
      </div>
      <div class='mb-3'><input type='checkbox' id='noShowEmptyBuyOrders' class='stateFilter' checked> Hide buy orders that you don't have enough of? </div>
      <div>Filter by Item:
        <select id='stateItemFilter' class='stateFilter'>
          <option></option>
          @foreach($buyOrderItems as $buyOrderItem)
              <option value='{{$buyOrderItem->id}}'>{{$buyOrderItem->name}}</div>
          @endforeach
        </select>
      </div>
      <div id='stateBuyOrders'  class='ms-3'>
@foreach ($buyOrders as $buyOrder)
  <?php
    $doTheyGotIt = \App\Items::doTheyHave(\App\ItemTypes
     ::find($buyOrder->itemTypeID)->name, $buyOrder->quantity);
  ?>
  <div class='mt-3 mb-3 stateBuyOrders stateItemType{{$buyOrder->itemTypeID}}
    @if (!$doTheyGotIt) unfillableBuyOrders d-none @endif'>

      <div>
       The State wants to buy {{number_format($buyOrder->quantity)}}
       <span class='fw-bold'>{{$buyOrder->name}}</span> for
       {{number_format($buyOrder->cost)}} clacks.
       ({{number_format($buyOrder->unitCost)}} each)
      </div>
      @if ($doTheyGotIt)
       <div class='text-center'>
         <button id='sellToState-{{$buyOrder->id}}'
           class='sellToState btn btn-success form-control'> sell (+{{number_format($buyOrder->cost)}} clacks)</button>
       </div>
       @endif
   </div>
@endforeach

      </div>
    </div>
