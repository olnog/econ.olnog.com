@extends('layouts.app')

@section('content')
<div class='main'>
  <ul class="nav justify-content-center nav-tabs mt-5">
    <li class="nav-item text-center">
      <a id='landNav' class="nav-link menu" href="#">land</a>
      (<span id='numOfParcels'></span>)

    </li>
    <li class="nav-item text-center">
      <a class="nav-link active menu" href="#">actions</a>
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
  <div class='fixed-top bg-opacity-100 row bg-secondary'>
    <div class='col-1'>
      <button class='feedback btn'><img src='/img/icons8-feedback-24.png'></button>
    </div><div class='col-11'>
      <div id='status' class='p-1 text-center  '></div>
      <div id='error' class='p-1 text-center text-danger'></div>
    </div>
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
    </div>
  </div><div id='land' class='d-none otherPages ms-3 mt-3 '>



  </div><div id='actions' class='otherPages ms-3'>

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
  </div><div class='mt-5 pt-5 text-center main'>
    <div>
      Thanks To:
    </div><div>
      <a href="https://icons8.com/icon/60641/building">Building icon by Icons8</a>
      <a href="https://icons8.com/icon/69053/landscape">Landscape icon by Icons8</a>
      <a target="_blank" href="">Sell</a> icon by <a target="_blank" href="">Icons8</a>
      <a href='https://icons8.com/icon/84998/buy'>Thanks for this buy icon too</a>
      <a target="_blank" href="https://icons8.com/icon/Qpt0cBZAp1GC/pacifier">Pacifier</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
      <a target="_blank" href="https://icons8.com/icon/61247/rent">Rent</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
      <a target="_blank" href="https://icons8.com/icon/hCFmpYC60qKc/feedback">Feedback</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>

    </div><div>
      <a href='https://freesound.org/people/suzenako/sounds/320905/'>The Ding</a>
      <a href='https://freesound.org/people/InspectorJ/sounds/415510/'>Bell, Counter,</a>
    </div>
    <div class='text-center pb-4 mt-5'>
      <a href='https://discord.gg/CjETTDYKdU'>[ Discord ]</a>
      <a href='/changes'>[ changelist ]</a>
      <a href='/help'> [ help ] </a>
    </div><div>

      <button class='feedback btn'><img src='/img/icons8-feedback-24.png'></button>
    </div><div>
      We'd love to hear from you. You can always let us know what you think from this button. (It's also always available in the top left corner.)

    </div>
    @if (\Auth::id() == 5)
      <div class='text-center mb-3 mt-5'>
        <a href='/actionTypes/create' class='m-3'>Action Types</a>

        <a href='/skillTypes' class='m-3'>Skill Types</a>
        <a href='/itemTypes' class='m-3'>Item Types</a>
        <a href='/buildingTypes' class='m-3'>Building Types</a>
      </div>
    @endif
  </div>



</div><div id='feedbackScreen' class='d-none'>
  <form method='POST' action='/feedback'>
    @csrf()
    <div class='fw-bold'>Feedback:</div>
    <div><textarea name='whatTheySaid' class='form-control' row=3 placeholder="feedback here"></textarea></div>
    <div class='fw-bold text-center'>Feels</div>
    <div class='row border'>
      <div class='col text-center text-success'>
        <input type='radio' name='feedbackFeeling' value='bad' class='me-1'>
        bad
      </div><div class='col text-center'>
        <input type='radio' name='feedbackFeeling' value='neutral' class='me-1' checked>
        neutral
      </div><div class='col text-center text-danger'>
        <input type='radio' name='feedbackFeeling' value='good' class='me-1'>
        good
      </div>
    </div><div class='row mt-5'>
      <div class='col'>
        <input type='button' id='cancelFeedback' class='btn btn-outline-danger form-control' value='Cancel'>
      </div><div class='col'>
        <button class='btn btn-outline-primary form-control'>Submit</button>
      </div>
    </div>
  </form>
</div>
@endsection
