
@foreach ($users as $user)
  <A href='/history/{{ $user->id }}'> {{ $user->name }} </a>
@endforeach
<h1> {{ $currentUser->name }}  - {{ $currentUser->created_at }}</h1>
