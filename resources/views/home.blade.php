@extends('layouts.app')

@section('content')
<div id='mainScreen'>
  <ul class="nav justify-content-center nav-tabs mt-5">
    <li class="nav-item text-center">
      <a id='landNav' class="nav-link menu" href="#">land</a>
      (<span id='numOfParcels'></span>)

    </li>
    <li class="nav-item text-center">
      <a class="nav-link active menu" href="#">labor</a>
      (<span id ='numOfPoints'></span>)
    </li>
    <li class="nav-item text-center">
      <a class="nav-link menu" href="#">items</a>
      (<span id='numOfItems'></span>)

    </li>
    <li class="nav-item text-center">
      <a class="nav-link menu" href="#">buildings</a>
      (<span class='builtBuildings'></span>)

    </li>
    <li class="nav-item text-center">
      <a class="nav-link menu" href="#">market</a>
      (<span id='numOfContracts'></span>)
    </li>

  </ul>
  <div class='fixed-top bg-opacity-100'>
    <div id='status' class='p-1 text-center bg-secondary '>&nbsp;</div>
    <div id='error' class='p-1 text-center text-danger'>&nbsp;</div>
  </div>
  <span id='csrf'>@csrf()</span>
  <div class='ms-2 row'>
    <div class='col-lg-3'>
      <span id='clacks' ></span> clacks

    </div><div class='col-lg-6 text-center'>
      <span id='hostileTakeover' class='text-danger d-none'>
        You are currently experiencing a hostile takeover. Go to land page to see more info.
      </span>
    </div><div class='col-lg-3 text-right'>
      <form id="logout-form" action="{{ route('logout') }}" method="POST"  class='text-right'>
          {{ csrf_field() }}
          Logged in as: <a href='/account'><span id='username' class='fw-bold'></span></a>
          <button class='btn btn-link'>[ logout ]</button>
      </form>

    </div><div class='text-center'>
      <button id='lastAction'class='btn btn-primary' disabled>Last Action?</button>
      <button id='startAutomation'class='btn btn-success' disabled>&#9658;</button>
      <button id='stopAutomation'class='btn btn-danger d-none' disabled>&#128721;</button>
    </div><div class='text-center mt-2'>

      Stop after doing this <input type='number' id='workHoursCent' min=0  value=0> times
    </div><div id='autoWorkStart' class='text-center'>
       <span id='autoActions'></span>/<span id='workHoursStop'></span>
    </div>
  </div><div id='land' class='d-none otherPages ms-3 mt-3 '>
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
          <select name='landTypeFilter' class='landFilter'>
            <option value='all' selected>All</option>
            <option value='jungle' >Jungle</option>
            <option value='forest' >Forest</option>
            <option value='mountains' >Mountains</option>
            <option value='plains' >Plains</option>
            <option value='desert' >Desert</option>
            </select>
        </div><div class='col'>
          Sort By:
          <select id='landSortByFilter' >
            <option value='null' selected>Parcel #</option>
            <option value='valuation' >Value</option>
            <option value='name' >Owner</option>
            <option value='type' >Land Type</option>
          </select>
        </div>
      </div>
    <div id='landTable'>


    </div>
  </div><div id='labor' class='otherPages ms-3'>
    <div class='text-center mt-3'>
        <button id='show-laborSupplement' class='show btn btn-link'> [ MORE ]</button>
        <button id='hide-laborSupplement' class='hide btn btn-link d-none'> [ LESS ]</button>
    </div><div id='laborSupplement' class='d-none'>
      <div>
        Consumables
        <button id='show-consumables' class='show btn btn-link'>[ + ]</button>
        <button id='hide-consumables' class='hide btn btn-link d-none'>[ - ]</button>
      </div><div id='consumables' class='d-none p-3'>
          <div>Food: <span id='laborFood'></span></div>
          Use:
          <input type='checkbox' id='useHerbMeds' > HerbMeds?
          (<span id='laborHerbMeds'></span>)
          <input type='checkbox' id='useBioMeds' > BioMeds?
          (<span id='laborBioMeds'></span>)
          <input type='checkbox' id='useNanoMeds' > NanoMeds?
          (<span id='laborNanoMeds'></span>)
      </div><div>
        Equipped: <span id='equipped'>nothing</span>
      </div><div>
        Special Equipment: <span id='specialEquipped'>nothing</span>
        <div class='fw-bold'>
          equipment
        </div><div id='equipmentListings' class='ms-3 pb-3'>    </div>
      </div>
    </div><div class='row'>
      <div class='col-lg-3 '>
        <span class='fw-bold'>Actions</span>
        <a href='/actionTypes/' class='me-3'>[ unlock ] </a>
        <a href='https://econ.olnog.com/help#actions'>[ help ]</a>
        <a href='/contracts/create?category=freelance' class='btn ms-3 createContract'>
          <img src='/img/icons8-sell-24.png'>
        </a>
      </div><div class='col-lg-9 '>
        <input type='checkbox' id='hideImpossible' class='formatActions'> Hide unavailable actions?
      </div><div>
      <span id='skillUnlocked' class=' fw-bold d-none'> You can unlock a new action!</span>
    </div>
    </div><div class=''>
      <div class="progress">
        <div id='skillPointProgress' class="progress-bar" role="progressbar"
          aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
      </div>

    </div><div id='actionsSection' class='ms-3'>
      <div id='newBuildingAvailable' class='fw-bold mt-2'>You've got a new building available to build!</div>
      <select id='buildingsThatCanBeBuilt' class='d-none'></select>
      <button id='build' class='btn btn-primary d-none' disabled>build</button>
      <div id='buildingCosts'>

      </div>
      <div id='actionListing' class='ps-3'></div>
    </div><div>
      <div id='freelanced' class=''>
        <div class=''>
          <span class='fw-bold'>freelancing</span> (pay clacks)
        </div><div id='freelanceActions' class='ps-3'>

        </div>
      </div> <div id='hired' class=''>
        <div class=''>
          <span class='fw-bold'>hiring</span> (receive clacks)
        </div><div id='hiredActions' class='ps-3'>
        </div>
      </div>
      <div id='robots' class='d-none'>
        <div class='fw-bold mt-5'>
          Robots <button id='robotStart' class='btn btn-success'>&#9658;</button>    <button id='robotStop'class='btn btn-danger d-none' >&#128721;</button>
          <span id='robotAnimation' class='d-none'></span>
        </div><div id='robotListing'>
        </div>
      </div>
    </div>
    <div id='' class='fw-bold mt-3'>
      Rebirth
      <button id='show-resetSection' class='show btn btn-link'>+</button>
      <button id='hide-resetSection' class='hide btn btn-link d-none'>-</button>
    </div>
    <div id='resetSection' class='d-none'>
      <div class='text-center p-3'>
      We understand that you might get yourself into bad spots so you can rebirth at any time. You will still get the 10% estate tax clack penalty.
      </div><div class='text-center'>
        <button id='reset' class='btn btn-danger form-control'>Rebirth</button>
      </div>
    </div>
    </div>
  </div><div id='buildings' class='d-none otherPages ms-3'>
    <div>
      <span class='fw-bold'>
        buildings
        (<span class='builtBuildings'></span>)
      </span> -
      <span id='numOfBuildingSlots'></span>
      free building slots
      <button id='show-buildingListings' class='show btn btn-link d-none'>+</button>
      <button id='hide-buildingListings' class='hide btn btn-link'>-</button>
      <a href='/buildingCosts' class='ms-5'> Why Can't I Build Anything?</a>
    </div><div>
      <div id='buildingWarning' class='text-decoration-underline text-center'></div>
      <input type='checkbox' id='filterFields' class='filterBuildings'> Hide Fields?
    </div><div id='buildingListings' class='p-3'>


    </div>
  </div><div id='items' class='d-none otherPages ms-3'>
      <span class='fw-bold'>items</span>
       <button id='show-itemSection' class='show btn btn-link d-none'>[ + ]</button>
       <button id='hide-itemSection' class='hide btn btn-link '>[ - ]</button>
    <div id='itemSection'>
      <div>
        <input type='checkbox' id='showOnlyInventory' checked> Show Only Items In Inventory?
      </div><div>
        <input type='checkbox' id='showDump'> Dump Items?(gone forever)

      </div><div id='itemListings' class='ms-3 pb-3'>
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
      <div>Filter by Item: <select id='stateItemFilter' class='stateFilter'></select></div>
      <div id='stateBuyOrders'  class='ms-3'>

      </div>
    </div>
  </div><div id='market'  class='d-none otherPages ms-3'>
  <div>
    <ul class="nav nav-tabs">
      <li class="nav-item ">
              <a class="nav-link active market" href="#">land</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link market" href="#">labor</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link market" href="#">items</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link market" href='#'>buildings</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link market" href='#'>mine</a>
      </li>
    </ul>
  </div>

  <div id='contractItemFilterDiv'>
    Filter By Item: <select id='contractItemFilter'></select>
    <input type='checkbox' class='contractFilterByCategory ms-3' value='buyOrder' checked> buying
    <input type='checkbox' class='contractFilterByCategory ms-3' value='sellOrder' checked> selling
  </div><div id='contractLandFilterDiv'>
    Filter By Land Type:
    <select id='contractLandFilter'>
      <option></option>
      <option>desert</option>
      <option>forest</option>
      <option>jungle</option>
      <option>mountains</option>
      <option>plains</option>
    </select>
  </div>
  <div id='newContactInContracts' class='text-center'></div>

  <div id='contractListings'></div>

  </div><div id='chat'  class='d-none otherPages ms-3'>
    <div class='text-center mt-2'>
      We also have a <a href='https://discord.gg/Ve7PjNBc'>Discord</a> if you need to contact me quickly.
    </div>
    <input type='checkbox' id='filterChat' checked> Show only chat messages?

    <div id='chatMsgs'></div>
    <div id='chatCreate' class='fixed-bottom row p-3'>
      <div class='col-9'>
        <input type='text' class='form-control'  id='chatContent' placeholder='type message here'>
      </div><div class='col-3 text-right'>
        <button id='chatSend' class='btn-primary btn'>send</button>
      </div>
    </div>
  </div><div class='mt-5 pt-5 text-center'>
    <div>
      Thanks To:
    </div><div>
      <a href="https://icons8.com/icon/60641/building">Building icon by Icons8</a>
      <a href="https://icons8.com/icon/69053/landscape">Landscape icon by Icons8</a>
      <a target="_blank" href="">Sell</a> icon by <a target="_blank" href="">Icons8</a>
      <a href='https://icons8.com/icon/84998/buy'>Thanks for this buy icon too</a>
      <a target="_blank" href="https://icons8.com/icon/Qpt0cBZAp1GC/pacifier">Pacifier</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
      <a target="_blank" href="https://icons8.com/icon/61247/rent">Rent</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
    </div><div>
      <a href='https://freesound.org/people/suzenako/sounds/320905/'>The Ding</a>
      <a href='https://freesound.org/people/InspectorJ/sounds/415510/'>Bell, Counter,</a>
    </div>
    <div class='text-center pb-4 mt-5'>
      <a href='https://discord.gg/CjETTDYKdU'>[ Discord ]</a>
      <a href='/changes'>[ changelist ]</a>
      <a href='/help'> [ help ] </a>
    </div>
  </div>

  @if (\Auth::id() == 5)
    <div class='mainScreen text-center mb-3 mt-5'>
      <a href='/actionTypes/create' class='m-3'>Action Types</a>

      <a href='/skillTypes' class='m-3'>Skill Types</a>
      <a href='/itemTypes' class='m-3'>Item Types</a>
      <a href='/buildingTypes' class='m-3'>Building Types</a>
    </div>
  @endif

</div>
<div class='forcedSkillScreen'>
  <div class='text-center fw-bold fs-3'>
    Please pick which skills you want.
  </div>  <div class='text-center mb-5'>
    You can always reset this later
    <a href='https://econ.olnog.com/help#rebirth'>(Rebirth)</a>,
    but you'll have to spend about 50% of your money. (Money is called clacks.)
  </div>
  <div id='forcedSkillListing'></div>
  <div class='forcedSkillScreen fixed-bottom'>
    <button id='quitForcedSkillScreen' class='btn btn-danger btn-lg form-control'>
      I'll do this later
    </button>
  </div>
</div>
@endsection
