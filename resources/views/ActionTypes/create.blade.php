@extends('layouts.app')

@section('content')
<div class='text-center'>
  <a href='{{ route('home')}}'>[ home ]</a>
</div>
<form id='updateActionTypeForm' method="POST" action="{{ route('actionTypes.store') }}" class='mt-3 mb-3'>
    @csrf
    <input type='hidden' name='whatWeDoing' value='update'>
  <div class='fw-bold'>
    Name:
  </div><div>
    <input type='text' id='actionName'name='actionName' class='form-control'>
  </div><div class='fw-bold'>
    Description:
  </div><div>
    <textarea id='actionDescription' name='actionDescription' class='form-control' rows=3>

    </textarea>
  </div><div class='mb-5'>
    <button id='createAction' class='btn btn-primary form-control'>create</button>
  </div>
</form>
@foreach ($actionTypes as $actionType)
  <div id='' class='fw-bold'>
    <span id='actionTypeName{{$actionType->id}}'>{{$actionType->name}}</span>
    <button id='updateActionType-{{$actionType->id}}' class='updateActionType btn btn-link'>[ update ]</button>
  </div><div id='actionTypeDescription{{$actionType->id}}' class='mb-3'>
    {{$actionType->description}}
  </div>


@endforeach
<script>

</script>
@endsection
