@foreach($chat as $chatMsg)
  <?php
    $divClass = "";
    $hours = round((strtotime('now') - strtotime($chatMsg->created_at))/ 60 / 60, 1);
    $time = $hours . " hours ";
    if ($hours >= 24){
      $time =round( $hours / 24, 1) . " days ";
    }
    $time .= " ago - ";
  ?>
  @if ($chatMsg->userID == null)
    <div class='m-3 allChat' style='color:grey;'>{{ $time }} {{$chatMsg->message}} </div>
  @endif

  @if ($chatMsg->userID != null)
    @foreach($users as $user)
      @if ($user->id == $chatMsg->userID)
        <?php $username = $user->name; ?>

      @endif
    @endforeach
    @if ($chatMsg->userID == Auth::id())
      <?php $divClass = " text-decoration-underline "; ?>
    @endif
    <div class='m-3 {{$divClass}} chitChat allChat'>
      {{$time }}
      <span class='fw-bold'>{{$username }}</span>: {{$chatMsg->message}}
    </div>
  @endif
@endforeach
