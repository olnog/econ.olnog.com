
<tr><th>Discovered</th><th>Parcel</th><th>Land Type</th><th>Owner</th><th>protected?</th><th>bribe</th><th>valuation</th></tr>
@foreach ($land as $parcel)
  <?php
  $ownerClass = '';
  $bribeButtons = '';
  $takeoverButton = '';
  $takeoverClass = '';
  $protectedCaption = $parcel['protected'] ? "yes" : "no";
  if (!$parcel['protected'] && $parcel['hostileTakeoverBy']> 0){
    $takeoverClass = ' takeovers ';
    $takeoverButton = "<div><a href='/land/" . $parcel['id'] . "'>hostile takeover</a></div><div>By <span class='fw-bold'>";
    $takeoverButton .= \App\User::find($parcel['hostileTakeoverBy'])->name;

  $takeoverButton .= "</span></div>";

  } else if (!$parcel['protected'] && $parcel['userID'] != \Auth::id()){
    $takeoverButton = "<button id='takeover-" . $parcel['id'] . "-"
      . $parcel['valuation'] . "' class='takeover btn btn-primary'>hostile takeover</button>";
  }
  if ($parcel['userID'] == \Auth::id()){
    $ownerClass = 'ownedLand fw-bold';

    $bribeButtons = "<button id='payBribe-" . $parcel['id']
      . "-1' class='payBribe btn btn-danger'>+1</button><button id='payBribe-"
      .  $parcel['id'] . "-10' class='payBribe btn btn-danger'>+10</button><button id='payBribe-"
      .  $parcel['id'] . "-100' class='payBribe btn btn-danger'>+100</button>";
  }
  ?>
  <tr id='parcel{{$parcel['id']}}' class=' {{$ownerClass}}  {{$takeoverClass}}  {{$parcel->type}} + " parcel'><td>{{substr($parcel->created_at, 0, 10)}} </td><td>Parcel #{{$parcel->id}}
  </td><td> {{$parcel->hostileTakeoverBy}} {{ $parcel->type }} </td><td> {{ $parcel->name }}</td><td><div>
    {{ $protectedCaption }}</div><div> {!!$takeoverButton !!}</div></td><td><div>
    {{ $parcel->bribe }}</div><div> {!! $bribeButtons !!}</div></td><td> {{ $parcel->valuation }}</td></tr>
@endforeach
