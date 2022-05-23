<?php
  $fieldsDisplayed = [];
 ?>
<div class='text-center'>

  @if (!$build)
    <div class='mt-3 text-decoration-underline'>
      You must <a href='/actionTypes'>unlock</a> the <span class='fw-bold'>build</span> action in order to build.
    </div>
  @endif

  @if (!$repair)
    <div class='text-decoration-underline'>
      You must <a href='/actionTypes'>unlock</a> the <span class='fw-bold'>repair</span> action in order to repair your building.
    </div>
  @endif
</div>
 <div class='mt-5 row'>
  <div class='col-md-2'>
   <span class='fw-bold'>
     buildings
     (<span class='builtBuildings'>{{count($buildings)}} / {{$buildingSlots}}</span>)
   </span>

   <button id='show-buildingListings' class='show btn btn-link d-none'>+</button>
   <button id='hide-buildingListings' class='hide btn btn-link'>-</button>
 </div><div class='col-md'>
   <a href='/buildingCosts' class=''> Why Can't I Build Anything?</a>
  </div>
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
        <?php
          array_push($fieldsDisplayed, $building->name);
          $harvest = strtotime(\App\Buildings::fetchOldestField($building->name, \Auth::id())->harvestAfter) - strtotime('now');
          $harvestCaption = " - " . $harvest . "s";
          if ($harvest > 86400){
            $harvestCaption = " - " . round($harvest / 86400, 1) . "d";
          } else if ($harvest > 3600){
            $harvestCaption = " - " . round($harvest / 3600, 1) . "h";
          } else if ($harvest > 3600){
            $harvestCaption = " - " . round($harvest / 60, 1) . "m";
          } else if ($harvest <= 0){
            $harvestCaption = "";
          }
         ?>
         {{$harvestCaption}}
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
