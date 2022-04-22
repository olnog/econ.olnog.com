@extends('layouts.app')

@section('content')
<a href='{{ route('home')}}'>[ home ]</a>
<form id='updateBuildingTypeForm' method="POST" action="{{ route('buildingTypes.store') }}" class='mt-3 mb-3'>
    @csrf
  <div>
    Building Name:
  </div><div>
    <input type='text' id='buildingName' name='name' maxlength=64>
  </div><div>
    Farming:
    <input type='checkbox' name='farming'  />
  </div><div>
    Skill
  </div><div>
    <input type='text' id='buildingSkill' name='skill' maxlength=64>
  </div><div>
    actions
  </div><div>
    <input type='text' id='buildingActions' name='actions' maxlength=128>
  </div><div>
    cost
  </div><div>
    <input type='text' id='buildingCost' name='cost' maxlength=64>
  </div><div>
    Description
  </div><div>
    <textarea id='buildingDescription' name='description'></textarea>

  </div><div>
    <button id='buildingTypeSubmit'>create</button>
  </div>

</form>


  @foreach ($buildingTypes as $buildingType)
    <div class='fw-bold'>
      <span id='buildingName-{{ $buildingType->id}}'>{{ $buildingType->name }}</span>
      <button id='updateBuildingType-{{ $buildingType->id }}'
        class='updateBuildingType btn btn-link'>[ update ]</button>
    </div><div id='' class='ms-3'>
      Skill: <span id='buildingSkill-{{ $buildingType->id}}'>{{$buildingType->skill}}</span>
    </div><div id='' class='ms-3'>
      Actions: <span id='buildingActions-{{ $buildingType->id}}'>{{ $buildingType->actions}}</span>
    </div><div id='' class='ms-3'>
      Cost: <span id='buildingCost-{{ $buildingType->id}}'>{{ $buildingType->cost}}</span>
    </div><div class='ms-3 text-decoration-underline'>
      <span id='buildingDescription-{{ $buildingType->id }}'>{{ $buildingType->description }}</span>
    </div>

  @endforeach


@endsection
