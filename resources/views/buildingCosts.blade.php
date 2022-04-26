@extends('layouts.app')

@section('content')
<a href="{{route('home')}}" class='fs-5'>back</a>
<div class='fw-bold mt-5 ms-3'>
Why am I not able to build right now?

</div><div class='text-decoration-underline ms-5'>
  @if (count($land) == 0)
    You don't have any land. Get some land by buying or leasing it, exploring or doing a hostile takeover.
  @elseif ($construction->rank == 0)
    You need to level up your Construction skill first. It's at 0.
  @elseif ($buildingSlots == 0)
    You don't have any building slots left. You'll have to get more land or destroy some buildings before you can build some more.
  @else
    You should be able to. Post in chat or the Discord so we can see if this is a bug.
  @endif
</div>
<div class='ms-3'>
  @foreach($buildingTypes as $buildingType)
    <div class='fw-bold mt-3'>
      {{$buildingType->name}}
      @if (\App\Buildings::isItBuilt($buildingType->name, \Auth::id()))
        <span class='fw-bold text-decoration-underline'>(built)</span>
      @endif
      <button id='show-buildingInfo{{$buildingType->id}}' class='show btn btn-link'> [ + ] </button>
      <button id='hide-buildingInfo{{$buildingType->id}}' class='hide btn btn-link d-none'> [ + ] </button>
    </div>
    <div id='buildingInfo{{ $buildingType->id }}' class='d-none ms-3'>
      <?php $buildingCost = \App\BuildingTypes::fetchBuildingCost($buildingType->name); ?>
      <div>Skill: {{$buildingType->skill}}</div>
      <div>Associated Action(s): {{$buildingType->actions}}</div>

      <div class='text-decoration-underline'>{{$buildingType->description}}</div>
      <div>Building Cost:</div>

      @foreach($buildingCost as $material=>$cost)
        <div class='ms-3'>
          {{$material}}: {{ number_format($cost) }}
          @if (\App\Items::doTheyHave($material, $cost))
            <span class='text-success'>&#10003;</span>
          @else
            <?php $stuff = \App\Items::fetchByName($material, Auth::id());?>
            <span class='text-danger'>X</span>
            [ You only have {{ number_format($stuff->quantity) }}. You need {{ number_format($cost - $stuff->quantity) }} more.]
          @endif
        </div>

      @endforeach
    </div>
  @endforeach
</div>
@endsection
