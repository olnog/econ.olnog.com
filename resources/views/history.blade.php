
@foreach ($users as $user)
  <A href='/history/{{ $user->id }}'> {{ $user->name }} </a>
@endforeach
<h1> {{ $currentUser->name }}  - {{ $currentUser->created_at }}</h1>
@foreach($history as $item)
  <div class='fw-bold'>{{$item->created_at}}</div>

  <div>{{$item->status}}</div>
@endforeach
