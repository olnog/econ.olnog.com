<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head><body>
<div class='text-center'>
  <a href='/home'>back</a>
</div>
<div id='helpTOC'></div>
<h1 id='concepts' class='text-center'>
  Concepts
</h1>
<div id='available' class='fw-bold'>
  Action Points
</div>
<div class='mb-3 ms-3'>
  You will get 1 new action point to unlock actions every time you do a certain
  number of actions. This number goes up each time you get an action point so
  you will get new actions points more slowly as you gain more and more actions.
</div>
<div id='rebirth' class='fw-bold'>
  Rebirth
</div>
<div class='ms-3'>
  When you do your rebirth, you keep all of your stuff
  (land, contracts, items, etc) but your actions and action points will be
  reset back to 0. (You can only Rebirth once every hour.) There is also a
  <span class='fw-bold text-danger'>50% tax</span> on your Clacks. Fortunately,
  you do get a few prestige perk options that are available. You can either:
  <div class='ms-3'>
    <span class='fw-bold'>Immortality</span> - restart with your actions still
    locked but your rank progress is saved and you get an action point back for each
    action. (requires one of your <span class='fw-bold'>Clones</span> - which
    can be created using a Clone Vat)
  </div><div class='ms-3'>
    <span class='fw-bold'>Legacy</span> - restart with your actions still
    locked but your rank progress is saved. (requires one of your
    <span class='fw-bold'>Children</span> - which can be created through a
    Reproduction contract in the market tab)
  </div>


</div>
<div id='hostileTakeover' class='fw-bold mt-3'>
  Hostile Takeover
</div>
<div class='ms-3'>
A player may attempt a hostile takeover on any unprotected land. Once a player
tries to seize the property by putting in a bid equal to twice the current
valuation, the owner may then put in their counter bid and a bidding war
starts. The highest bid wins. Players have 24 hours to respond to each bid
and once no one responds to a bid with a higher bid, that person wins.  Outside
of the first bid, the minimum for bidding is equal to 1.10% of the previous
bid. (this is arbitrary and can be changed later depending on how it works out)
</div>
<div class='ms-3'>
The challenger will always lose their bids and the owner of the property will always get a new valuation in addition to the bids.
</div>
<div class='fw-bold ms-3'>
If the owner wins
</div>
<div class='ms-4'>
    • Owner loses their first counterbid; gains new valuation and all additional bids they made after their first counterbid
</div>
<div class='ms-4'>
    • Challenger loses all bids
</div>
<div  class='fw-bold ms-3'>
If owner loses
</div>
<div class='ms-4'>
    • Owner loses property; keeps their first counterbid
</div>
<div class='ms-4'>
    • Challenger loses all bids, but gains property at new valuation
</div>

<div id='materialDurability' class='fw-bold'>
  Tool Material Durability (Stone, Iron, Steel)
</div><div>
  Stone tools will give you 100 uses, iron tools will give you 1,000 uses and
  steel tools will give you 10,000 uses.
</div>

<div id='land' class='fw-bold'>
  Land
</div><div>
  Land can be found by exploring. You can increase your minimum chance from
  1 to 100 by having a Car equipped with the associated fuel in supply or
  increase it to 10,000 by owning a satellite with the pre-requisite amount of
  Electricity.
</div><div>
  You can also take over other player's land by launching a
  <a href='#hostileTakeover'>hostile takeover</a>.
</div><div>
  Alternatively, if you're trying to get land to get a certain building,
  maybe someone is already leasing out that building out?
</div>

<div id='landBonus' class='fw-bold'>
  Land Bonus
</div><div>
  Players get a bonus for the more land they have in chopping trees (Forest),
  mining (Mountains) and planting & harvesting rubber plantations (Jungle).
  Each land type adds one more to base yield.
</div>



<h1  id='faq' class='text-center'>
FAQ
</h1>
<div id='faq-2' class='fw-bold'>
  How do I get money?
</div>
<div class='ms-4'>
    Check contracts to see what other players are buying - whether that's
    labor or items. Also, nearly every item can be sold to The State under
    the state tab if you have the appropriate quantity. Once you've established
    yourself, set up your own contracts to sell stuff of your own.
</div>
<div id='tabs-1' class='fw-bold'>
  What do the tabs do?
</div>
<div class='ms-3'>
  <span class='fw-bold'>land</span> - all land currently in the game
</div>
<div class='ms-3'>
  <span class='fw-bold'>actions</span> - all actions you've unlocked, actions
  you can hire other people to
  do, and actions that will earn you money (the relevant equipment you can
  equip is under [ MORE ] but you will auto-equip equipment for an action
  if it is there and has the relevant fuel)
</div>
<div class='ms-3'>
  <span class='fw-bold'>items</span> - all items you own, the items you can
  sell to The State for money
  and items you can equip (once an item is equipped, it becomes equipment
  available in the actions tab and cannot be sold)
</div>
<div class='ms-3'>
  <span class='fw-bold'>buildings</span> - all buildings you have built
</div>
<div class='ms-3'>
  <span class='fw-bold'>market</span> - create or fulfill contracts to buy
  land, sell land, buy items,
  sell items, repair buildings, build buildings, hire labor, or sell labor
  with other players
</div>



<h1 id='buildings' class='text-center'>
  Buildings
</h1>
@foreach ($buildingTypes as $buildingType)
  <div class='mb-3'>
    <div>
      <span id='building-{{$buildingType->id}}' class='fw-bold'>
        {{$buildingType->name}}
      </span>

      <a href='#helpTOC'>[ top ]</a>
    </div>
    <div class='ms-3'><span class='fw-bold text-secondary'>Description</span>: {!!$buildingType->description!!}</div>
    <div class='ms-3'><span class='fw-bold text-secondary'>Associated Actions</span>:
    <?php
      $actionArr = explode(',', $buildingType->actions);
    ?>
    @foreach($actionArr as $action)
      <?php
        $actionType = \App\ActionTypes::where('name', trim($action))->first();
        $actionName = $action;
        if ($actionType != null){
          $actionName = "<a href='#actionType-" . $actionType->id
          . "'>" . $action . "</a>";
        }
      ?>
       <span class='me-3'>{!!$actionName!!}</span>
    @endforeach
    </div>
    <?php $buildingCost = \App\BuildingTypes::fetchBuildingCost($buildingType->name); ?>
    @if ($buildingCost != null)
      <div class='ms-3 fw-bold text-secondary'>Build Cost:</div>
      @foreach ($buildingCost as $material=>$cost)
        <div class='ms-4'>
          <span class='text-decoration-underline'>{{$material}}</span>: {{number_format($cost)}}
        </div>
      @endforeach
    @endif
  </div>
@endforeach


<h1 id='items' class='text-center'>
  Items
</h1>
@foreach ($itemTypes as $itemType)
  <div class='fw-bold mt-3'>
    <span id='item{{$itemType->id}}'>
      {{$itemType->name}}
      @if ($itemType->material != null)
      ({{$itemType->material}} / {{$itemType->durability}})
      @endif
    </span>
    <a href='#helpTOC'>[ top ]</a>
  </div>
<div>
    {{$itemType->description}}
  </div>

@endforeach

<h1  class='text-center'>
  Any more questions?
</h1>
<div>
  If you have any more questions, feel free to post them in chat. Or hit me up on
  <a href='https://www.reddit.com/user/olnog/'>reddit</a> or <a href='https://twitter.com/therealolnog'>twitter</a> or <a href='https://discord.gg/CjETTDYKdU'>Discord</a>
</div>

<h1 id='actions'>Actions</h1>
@foreach($actionTypes as $actionType)
  <?php
    $item = trim(\App\Items::fetchItemNameForAction($actionType->name));
    $itemType = \App\ItemTypes::where('name', $item)->first();
    if (str_contains($actionType->description, $item) && $itemType != null){
      $actionType->description = str_replace($item, "<a href='#item" . $itemType->id . "' >" . $item . "</a>", $actionType->description);
    }
    $buildings = \App\Buildings::fetchRequiredBuildingsFor($actionType->name);
    if ($buildings != null){
      foreach($buildings as $building){
        $buildingType = \App\BuildingTypes::where('name', $building)->first();
        $actionType->description = str_replace($building, "<a href='#building-" . $buildingType->id . "' >" . $building . "</a>", $actionType->description);
      }
    }
  ?>
  <div id='actionType-{{$actionType->id}}' class='actionType fw-bold'>
    {{$actionType->name}} <a href='#helpTOC'>[ top ]</a>
  </div><div class='ms-3 mb-3 me-3'>
    {!!$actionType->description!!}
  </div>
@endforeach

<script src='https://code.jquery.com/jquery-3.6.0.js'></script>

<script>
  let myIDs = document.querySelectorAll('*[id]')
  let html = ''
  for (let i in myIDs){
    let headingArr = ['Concepts', 'FAQ', 'Items', 'Any more questions?', 'Actions', 'Buildings']
    let headingsClass = 'ms-5'
    if ($('#' + myIDs[i].id).html() == undefined){
      continue
    }
    if (headingArr.includes($('#' + myIDs[i].id).html().trim())){
      headingsClass = "fw-bold ms-3"
    }
    html += "<div class='" + headingsClass + " '><a href='#" + myIDs[i].id + "'>" + $('#' + myIDs[i].id).html() + "</a></div> "

  }
  $("#helpTOC").html(html)

</script>
</body></html>
