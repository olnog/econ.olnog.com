@extends('layouts.app')

@section('content')
<div class='sticky-top bg-opacity-100 row bg-secondary'>
  <div class='col-md-1 col-3'>
    <button class='feedback btn'><img src='/img/icons8-feedback-24.png'></button>
    <a href='/help'><img src='/img/icons8-help-24.png'></a>
  </div><div class='col'>
    <div id='status' class='text-center p-1 '></div>
    <div id='error' class='text-center p-1'></div>
  </div>
</div>
<div class='main'>
  <ul class="nav justify-content-center nav-tabs mt-5">
    <li class="nav-item text-center">
      <a id='landNav' class="nav-link menu" href="#">land</a>
      (<span id='numOfParcels'></span>)

    </li>
    <li class="nav-item text-center">
      <a class="nav-link active menu" href="#">actions</a>
      (<span id='numOfUnlocked'></span>/<span id ='numOfPoints'></span>)
    </li>
    <li class="nav-item text-center">
      <a class="nav-link menu" href="#">items</a>
      (<span id='numOfItems'></span>)

    </li>
    <li class="nav-item text-center">
      <a class="nav-link menu" href="#">buildings</a>
      (<span class='builtBuildings'></span>/<span id='buildingSlots'></span>)

    </li>
    <li class="nav-item text-center">
      <a class="nav-link menu" href="#">market</a>
      (<span id='numOfContracts'></span>)
    </li>

  </ul>

  <span id='csrf'>@csrf()</span>
  <div class='ms-2 row'>
    <div class='col-3 '>
      <span id='clacks' ></span> clacks
    </div><div class='col-9 text-end'>
      <form id="logout-form" action="{{ route('logout') }}" method="POST"  class='text-end'>
          {{ csrf_field() }}
          Logged in as: <a href='/account'><span id='username' class='fw-bold'></span></a><button class='btn btn-link'>[ logout ]</button>
      </form>

    </div>
  </div>
  @if($hostileTakeover)
    <div class='row text-center' >
      <span id='hostileTakeover' class='text-danger'>
        You are currently experiencing a hostile takeover. Go to land page to see more info.
      </span>
    </div>
  @endif
  <div class='text-center text-danger fw-bold'>
    
  </div>
  <div class='row'>
    <div class='text-center mt-3'>
      <button id='lastAction'class='btn btn-primary action' disabled>Last Action?</button>
      <button id='startAutomation'class='btn btn-success' disabled>&#9658;</button>
      <button id='stopAutomation'class='btn btn-danger d-none' disabled>&#128721;</button>
    </div><div class='text-center mt-2'>
      <div>
      Stop after doing this <input type='number' id='workHoursCent' min=0  value=0> times
      </div><div>
        @if ($offlineMinutes > 0)
          You will continue to do actions offline for
          @if ($offlineMinutes < 60)
            {{$offlineMinutes}} minutes.
          @elseif ($offlineMinutes < 1440)
            {{round($offlineMinutes / 60, 1)}} hours.
          @else
            {{round($offlineMinutes / 1440, 1)}} days.
          @endif
        @endif
      </div>
    </div>
  </div><div id='land' class='d-none otherPages ms-3 mt-3 '>


  </div><div id='actions' class=' otherPages ms-3'>

  </div><div id='buildings' class='d-none otherPages ms-3'>

  </div><div id='items' class='d-none otherPages ms-3'>

  </div><div id='market'  class='d-none otherPages ms-3'>
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
      <a target="_blank" href="https://icons8.com/icon/61517/job">Job</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
      <a target="_blank" href="https://icons8.com/icon/83244/help">Help</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
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
      <div class='col text-center text-danger'>
        <input type='radio' name='feedbackFeeling' value='bad' class='me-1'>
        bad
      </div><div class='col text-center'>
        <input type='radio' name='feedbackFeeling' value='neutral' class='me-1' checked>
        neutral
      </div><div class='col text-center text-success'>
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
