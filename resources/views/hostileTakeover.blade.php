@extends('layouts.app')

@section('content')
<a href="{{route('home')}}"> home </a>
<span id='csrf'>
@csrf()
</span>
  <h1 class='text-center'>Parcel #{{$land->id}}</h1>
  <div class='text-center'>
    This parcel is undergoing a hostile takeover by <span class='fw-bold'>{{ $attacker->name}}</span>. Once 24 hours has passed after the last bid, that last bidder wins.
  </div><div class='text-center p-1'>
    Either way, <span class='fw-bold'>{{ $attacker->name}}</span> will lose all of the money they bid.
  </div><div class='text-center p-1'>
    <span class='fw-bold'>{{ $owner->name }}</span> will lose their first counterbid but will regain all
    subsequent counterbids <span class='fw-bold'>if they win</span> the hostile takeover.
  </div><div class='text-center p-1'>
    Otherwise, <span class='fw-bold'>if they
    lose</span>, they gain the first counterbid but lose all subsequent counterbids
    and their parcel.
  </div><div class='text-center p-3'>
    The last bid was {{ round((strtotime('now') - strtotime($lastBid->created_at)) / 60 / 60, 1) }}h ago.
    {{ 24 - round((strtotime('now') - strtotime($lastBid->created_at)) / 60 / 60, 1) }} more hours to go until bidding is completed.
  </div>
  <div id='error' class='text-danger text-center'></div>
  <div class='fw-bold mt-3'>{{ $owner->name }}</div>
  @if (count($ownerBids) == 0)
    <div class='ms-3'>They have no bids yet.</div>
  @else
    @foreach($ownerBids as $bid)
      <div class='ms-3'>
        Bid #{{ $bid->bidNum }}  - {{ $bid->amount }} clacks
         - {{ round((strtotime('now') - strtotime($bid->created_at)) / 60 / 60, 1) }}h ago
      </div>
    @endforeach
  @endif

  <?php $bidderID = $land->hostileTakeoverBy;  ?>
  @if ($lastBid->userID != \Auth::id() && $land->hostileTakeoverBy == $lastBid->userID)
    <div  class='mt-5 text-center'>
      Place your bid:
      <input type='text' id='bidAmount' value='{{ceil($lastBid->amount * 1.1)}}'>
      <button id='bid-{{$lastBid->landID}}' class='bid'>bid</button>
    </div>
  @elseif ($lastBid->userID == \Auth::id() && $land->userID == $lastBid->userID)
  <div class='text-center'> You are waiting for them to make a counter-bid.</div>
  @endif

  <div class='fw-bold mt-3'>{{ $attacker->name }}</div>
  @foreach($counterBids as $bid)
    <div class='ms-3'>
      Bid #{{ $bid->bidNum }}   - {{ $bid->amount }} clacks
       - {{ round((strtotime('now') - strtotime($bid->created_at)) / 60 / 60, 1) }}h ago
    </div>
  @endforeach

  @if ($lastBid->userID != \Auth::id() && $land->userID == $lastBid->userID)
    <div  class='mt-5 text-center'>
      Place your bid:
      <input type='text' id='bidAmount' value='{{ceil($lastBid->amount * 1.1)}}'>
      <button id='bid-{{$lastBid->landID}}' class='bid'>bid</button>
    </div>
  @elseif ($lastBid->userID == \Auth::id() && $land->hostileTakeoverBy == $lastBid->userID)
    <div class='text-center'> You are waiting for them to make a counter-bid.</div>
  @endif
@endsection
