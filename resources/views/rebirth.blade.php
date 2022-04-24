@extends('layouts.app')

@section('content')

  <form method='POST' action='/rebirth'>
    @csrf()
    <div>
      It's time for your Rebirth. You'll lose all your actions you've unlocked and each action will be reset back to 1, but you'll get some points back to unlock some again.
    </div><div class='mt-3'>
      Just so you know, there's a penalty on your clacks each time you Rebirth. ({{$tax * 100}}%)
    </div><div class='mt-3'>
      Which would you rather do?
    </div><div class='ms-3'>
      <input type='checkbox' name='legacy' @if($children < 1) disabled @endif>
      Legacy - start your next game with your actions still locked but your
      rank progress is saved. (requires one of your Children - which can be
      created through a Reproduction contract)
    </div><div class='mt-5'>
      <button class='btn btn-primary form-control'>rebirth</button>
    </div>
  </form>
@endsection
