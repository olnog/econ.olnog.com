@extends('layouts.app')

@section('content')
  <div class='text-center'>
    <a href="{{route('home')}}">[ Home ]</a>
  </div><div class='text-center'>
    You have {{$labor->availableSkillPoints }} point(s) to unlock a skill with.
  </div>
  @foreach($actionTypes as $actionType)
    <div>
      <?php $action = \App\Actions::fetchByName(\Auth::id(), $actionType->name); ?>
      @if ($action->unlocked == false)
      <form method='POST' action="/actionTypes/{{$actionType->id}}">
      @csrf()
      {{ @method_field('PUT') }}
      <button id='incrementSkill-{{$actionType->id}}' class='btn btn-outline-success me-3'>+</button>
      <span class='fw-bold'>{{$actionType->name}}</span>

      <a href='#' id='show-actionTypeDescription{{$actionType->id}}' class='ms-3 show  '>[ + ]</a>
      <a href='#' id='hide-actionTypeDescription{{$actionType->id}}' class='ms-3 hide d-none'>[ - ]</a>
      </form>

      @else
        &#10003;
        <span class='fw-bold'>{{$actionType->name}}</span>
        ({{$action->rank}})
        <a href=# id='show-actionTypeDescription{{$actionType->id}}' class='ms-3 show  '>[ + ]</a>
        <a href='#' id='hide-actionTypeDescription{{$actionType->id}}' class='ms-3 hide d-none'>[ - ]</a>
      @endif
      @if ($action->unlocked == true)
        <div class="progress">
          <div class="progress-bar" role="progressbar" style="width: {{round($action->totalUses / $action->nextRank * 100)}}%"
            aria-valuenow="{{$action->totalUses}}" aria-valuemin="0" aria-valuemax="{{$action->nextRank}}"></div>
        </div>
      @endif
    </div><div id='actionTypeDescription{{$actionType->id}}' class='d-none pb-3'>
      {{$actionType->description}}
    </div>
  @endforeach
@endsection