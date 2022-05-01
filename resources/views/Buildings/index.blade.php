<?php
  $fieldsDisplayed = [];
 ?>
@foreach($buildings as $building)
  @continue (in_array($building->name, $fieldsDisplayed))
  <div class='mt-3 @if ($building->farming) fields @endif'>
    <div>
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
