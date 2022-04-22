@extends('layouts.app')
@section('content')
  <a href='/' class='m-5'>back</a>
  <h1 class='text-center'>{{$user->name}}</h1>
  <div class='ms-5'><span class='fw-bold'>Joined:</span> {{$user->created_at}}</div>
  <span id='csrf'>@csrf()</span>
  <div class='text-center m-3'>
    Auto-bribing every day at:
    <input type='number' id='autoBribe' value='{{$user->autoBribe}}' style='width:100px;'>
    <button id='setAutoBribe' class='btn btn-primary'>set</button>
  </div><div class='text-center m-3'>
    <input type='checkbox' id='soundSetting' class='settings' @if($user->soundSetting) checked @endif> Sound On?
    <input type='checkbox' id='eatFoodSetting' class='settings' @if($user->eatFoodSetting) checked @endif> Eat Food?
    <input type='checkbox' id='useHerbMedsSetting' class='settings' @if($user->useHerbMedsSetting) checked @endif> Use HerbMeds?
    <input type='checkbox' id='useBioMedsSetting' class='settings' @if($user->useBioMedsSetting) checked @endif> Use BioMeds?
    <input type='checkbox' id='useNanoMedsSetting' class='settings' @if($user->useNanoMedsSetting) checked @endif> Use NanoMeds?
  </div><div class='text-center mt-3'>
    <input type='checkbox' value='action' class='historyFilter' checked> action
    <input type='checkbox' value='bid' class='historyFilter' checked> bid
    <input type='checkbox' value='buildings' class='historyFilter' checked> buildings
    <input type='checkbox' value='contract' class='historyFilter' checked> contract
    <input type='checkbox' value='land' class='historyFilter' checked> land
    <input type='checkbox' value='lease' class='historyFilter' checked> leases
    <input type='checkbox' value='reset' class='historyFilter' checked> resets
    <input type='checkbox' value='skills' class='historyFilter' checked> skills
    <input type='checkbox' value='state' class='historyFilter' checked> state

  </div>
  @foreach ($history as $entry)
    <div class='history {{$entry->type}}'>
      <div class='text-center fw-bold'> {{$entry->created_at}}</div>
      <div class='text-center mb-3'> {{ $entry->status }} </div>
    </div>
  @endforeach
@endsection
