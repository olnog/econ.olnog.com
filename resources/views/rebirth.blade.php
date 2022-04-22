@extends('layouts.app')

@section('content')

  <form method='POST' action='/rebirth'>
    @csrf()
    <div>
      You've used up all your work hours. Now it's time for your Rebirth.

    </div><div class='mt-3'>
      Just so you know, there's a penalty on your clacks each time you Rebirth. ({{$tax * 100}}%)
    </div><div class='mt-3'>
      Which would you rather do?
    </div><div class='ms-3'>
      <input type='checkbox' name='childProdigy' @if($labor->maxSkillPoints < 25) disabled @endif>
      Child Prodigy - trade 10 max skill points for 1 additional available skill points (min of 25 max skill points required)
    </div><div class='ms-3'>
      <input type='checkbox' name='genius' @if($books < $labor->maxSkillPoints) disabled @endif>
      Genius - increase your maximum skill point capacity by 1 (requires {{ $labor->maxSkillPoints }} Books )
    </div><div class='ms-3'>
      <input type='checkbox' name='legacy' @if($children < 1) disabled @endif>
      Legacy
      <select name='legacySkillTypeID'>
          <option></option>
          @foreach($skills as $skill)
            <option value='{{$skill->id}}' @if ($labor->legacySkillTypeID == $skill->id) selected @endif>
              {{$skill->name }} {{$skill->rank}}
            </option>
          @endforeach
      </select>
      - start your next game with the same amount of points in this skill as you have now (requires one of your Children (created from Reproduction contract))
    </div><div class='mt-5'>
      <button class='btn btn-primary form-control'>rebirth</button>
    </div>
  </form>
@endsection
