@extends('layouts.app')
<a href='{{ route('home')}}'>[ home ]</a>
@section('content')
<form id='skillTypeForm' method="POST" action="{{ route('skillTypes.store') }}" class='mt-5'>
    @csrf

  <div>
    Skill Name:
  </div><div>
    <input type='text' id='skillTypeName' name='name' maxlength=64 class='form-control'>
  </div><div>
    Skill Identifier:
  </div><div>
    <input type='text' id='identifier' name='identifier' maxlength=64  class='form-control'>
  </div><div>
    Description
  </div><div>
    <textarea id='skillTypeDescription' name='description'  class='form-control'></textarea>
  </div><div>
    Buildings:
  </div><div>
    <input type='text' name='buildings' maxlength=64  class='form-control'>
  </div><div>
    Equipment:
  </div><div>
    <input type='text' name='equipment' maxlength=64  class='form-control'>
  </div><div>
    <button id='skillTypeSubmit' >create</button>
  </div>

</form>

  Please make sure to check that a skill is made for each user after you make a new skill type!!!!!
  @foreach ($skillTypes as $skillType)
    <div class='fw-bold'>
      #{{$skillType->id}}
      <span id='skillTypeName-{{ $skillType->id }}'>
        {{ $skillType->name }}
      </span>
      [
      <span id='skillTypeIdentifier-{{ $skillType->id }}'>
        {{ $skillType->identifier }}
      </span>
      ]
      <button id='updateSkillType-{{ $skillType->id }}' class='updateSkillType btn btn-link'>[ update ]</button>
    </div><div id='skillTypeDescription-{{ $skillType->id }}' class='mb-3'>
      {{ $skillType->description }}
    </div><div id='skillTypeBuildings-{{ $skillType->id }}' class='mb-3'>
      {{ $skillType->buildings }}
    </div><div id='skillTypeEquipment-{{ $skillType->id }}' class='mb-3'>
      {{ $skillType->equipment }}
    </div>

  @endforeach


@endsection
