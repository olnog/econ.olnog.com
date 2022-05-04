<?php
  $fieldsDisplayed = [];
 ?>
<div class='text-center'>
  @if (!$build || !$repair)
    <div class='mt-3'>
      <a href='/actionTypes'>[ unlock ]</a>
    </div>
  @endif
  @if (!$build)
    <div class='mt-3 text-decoration-underline'>
      You must unlock the <span class='fw-bold'>build</span> action in order to build.
    </div>
  @endif

  @if (!$repair)
    <div class='text-decoration-underline'>
      You must unlock the <span class='fw-bold'>repair</span> action in order to repair your building.
    </div>
  @endif
</div>
 <div class='mt-5'>
   <span class='fw-bold'>
     buildings
     (<span class='builtBuildings'>{{count($buildings)}}</span>)
   </span> -
   <span id='numOfBuildingSlots'>{{$buildingSlots}}</span>
   free building slots
   <button id='show-buildingListings' class='show btn btn-link d-none'>+</button>
   <button id='hide-buildingListings' class='hide btn btn-link'>-</button>
   <a href='/buildingCosts' class='ms-5'> Why Can't I Build Anything?</a>
 </div><div>
   <div id='buildingWarning' class='text-decoration-underline text-center'></div>
   <!--<input type='checkbox' id='filterFields' class='filterBuildings'> Hide Fields?-->
 </div><div id='buildingListings' class='p-3'>
@foreach($buildings as $building)
  @continue (in_array($building->name, $fieldsDisplayed))
  <div class='mt-3 @if ($building->farming) fields @endif'>
    <div>
      @if ($building->uses < $building->totalUses
        && in_array($building->id, $repairable ))
        <button id='repair-{{$building->id}}' class='repair btn'>
          <img src='/img/icons8-job-30.png'>
        </button>

      @endif
      <span class='@if ($building->uses == 0 && !$building->farming) text-danger @endif '>

       {{$building->name}}
       @if (!$building->farming)
        {{round($building->uses / $building->totalUses * 100, 2)}}%
       @elseif ($building->farming && !in_array($building->name, $fieldsDisplayed))
        [{{\App\Buildings::howManyFields($building->name, \Auth::id())}}]
        <?php array_push($fieldsDisplayed, $building->name); ?>
       @endif
     </span>

    <button id='show-buildingButtons{{$building->id}}'
      class='show btn btn-link'>+</button>
    <button id='hide-buildingButtons{{$building->id}}'
      class='hide btn btn-link d-none'>-</button>


    </div><div id='buildingButtons{{$building->id}}' class='d-none'>
      <button id='destroyBuilding-{{$building->id}}'
        class='destroyBuilding m-3 btn btn-warning me-3'>destroy</button>
      @if ($building->uses < $building->totalUses
        && in_array($building->id, $repairable ))
        <button id='repair-{{$building->id}}' class='m-3 repair btn btn-info'>
          repair
        </button>
      @endif
    </div>
  </div>

@endforeach
</div>
