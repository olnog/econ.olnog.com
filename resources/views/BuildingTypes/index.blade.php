@extends('layouts.app')

@section('content')
<a href='{{ route('home')}}'>[ home ]</a>
<form id='updateBuildingTypeForm' method="POST" action="{{ route('buildingTypes.store') }}" class='mt-3 mb-3'>
    @csrf
  <div>
    Building Name:
  </div><div>
    <input type='text' id='buildingName' name='name' maxlength=64 class='form-control'>
  </div><div>
    Actions
  </div><div>
    <input type='text' id='buildingActions' name='actions' class='form-control'>
  </div><div>
    Description
  </div><div>
    <textarea id='buildingDescription' name='description' class='form-control'></textarea>

  </div><div>
    Farming:
    <input type='checkbox' name='farming'  />
  </div>

  <div>
    <button id='buildingTypeSubmit'>create</button>
  </div>

</form>


  @foreach ($buildingTypes as $buildingType)
    <div class='fw-bold'>
      <span id='buildingName-{{ $buildingType->id}}'>{{ $buildingType->name }}</span>
      <button id='updateBuildingType-{{ $buildingType->id }}'
        class='updateBuildingType btn btn-link'>[ update ]</button>
    </div><div id='' class='ms-3'>
      Actions: <span id='buildingActions-{{ $buildingType->id}}'>{{ $buildingType->actions}}</span>
    </div><div class='ms-3 text-decoration-underline'>
      <span id='buildingDescription-{{ $buildingType->id }}'>{{ $buildingType->description }}</span>
    </div>

  @endforeach


@endsection
