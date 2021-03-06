@extends('layouts.app')
@section('content')
  <div class='text-center'>
    <a href="{{route('home')}}">[ Home ]</a>
  </div><div class='text-center'>
    You have {{$labor->availableSkillPoints }} point(s) to unlock a skill with.
  </div><div class='text-center fw-bold'>
    <input type='checkbox' id='showOnlyActionsYouCanDo' class='filterActionTypes' checked>
    Show Only Actions You Can Do Now
  </div>
  @foreach($actionTypes as $actionType)
    <div class="@if(in_array($actionType->name, $actionable)) yesDo @else noDo d-none @endif mt-3">
      <?php $action = \App\Actions::fetchByName(\Auth::id(), $actionType->name); ?>
      <?php $hireContract = \App\Contracts::fetchHighestHireContract($actionType->name); ?>

      @if ($action->unlocked == false && $labor->availableSkillPoints > 0)
      <form method='POST' action="/actionTypes/{{$actionType->id}}">
      @csrf()
      {{ @method_field('PUT') }}
      <input type='hidden' name='whatWeDoing' value='notUpdating'>
      <a href='#' id='show-actionTypeDescription{{$actionType->id}}' class='ms-3 show  '>[ + ]</a>
      <a href='#' id='hide-actionTypeDescription{{$actionType->id}}' class='ms-3 hide d-none'>[ - ]</a>
      <span class='fw-bold'>{{$actionType->name}}</span>
      @if ($action->unlocked || $action->totalUses > 0)
        {{$action->rank}}x

      @endif
      <button id='incrementSkill-{{$actionType->id}}' class='btn btn-outline-success me-3'>+</button>
      @if ($hireContract != null)
        Someone is hiring for this action at {{$hireContract->price}} clack(s) each time.

      @endif
      </form>

      @else

        <a href=# id='show-actionTypeDescription{{$actionType->id}}' class='ms-3 show  '>[ + ]</a>
        <a href='#' id='hide-actionTypeDescription{{$actionType->id}}' class='ms-3 hide d-none'>[ - ]</a>
        <span class='fw-bold'>{{$actionType->name}}</span>
        @if ($action->unlocked)
          {{$action->rank}}x
          &#10003;
        @endif
        @if ($hireContract != null)
          Someone is hiring for this action at {{$hireContract->price}} clack(s) each time.

        @endif
      @endif

      @if ($action->unlocked == true || $action->totalUses > 0)
        <div class="progress">
          <div class="progress-bar" role="progressbar" style="width: {{round($action->totalUses / $action->nextRank * 100)}}%"
            aria-valuenow="{{$action->totalUses}}" aria-valuemin="0" aria-valuemax="{{$action->nextRank}}"></div>
        </div>
      @endif

    </div><div id='actionTypeDescription{{$actionType->id}}' class='d-none pb-3'>
      {!!$actionType->description!!}
    </div>
  @endforeach
@endsection
