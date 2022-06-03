@extends('layouts.app')

@section('content')
      @if (Route::has('login'))
        @auth
          <a href="{{ url('/home') }}">Home</a>
        @else
          <div class="container">
            <h1 class='text-center'>
              Idle Incremental Meets The Economic Genre
            </h1>
            <div class='text-center'>
              <button id='show-aboutSection' class='show btn btn-link'>[ MORE ] </button>
              <button id='hide-aboutSection' class='hide btn btn-link d-none'>[ LESS ] </button>
            </div>
            <div id='aboutSection' class='d-none'>
              <div class='p-1'>
                Hey, this is my new prototype. It's my interpretation of an idle incremental meets the economic game genre.
              </div><div class='p-1'>
                Industry Idle was a really great step in the right direction, but it was still a lil too logistic based. I loved its market system but I would have liked to have seen more market.
              </div><div class='p-1'>
                There were other browser games I've stumbled upon, but both Virtonomics and some other one seemed really convoluted and pay 2 play. So this is what I'm trying to build:
              </div><div class='p-1'>
                I want everything in the game to be tradable with other players, and I want everything in the game to have layers of interaction (an economy). (I, ideally, would have started with player-made currencies but I thought that would block too much trading.)
              </div>
            </div>
          <div class="row justify-content-center p-5">
          <div class="col-md-8">
          <div class="card">
          <div class="card-header">{{ __('Login') }}</div>

          <div class="card-body">
          <form method="POST" action="{{ route('login') }}">
          @csrf

          <div class="form-group row">
          <label for="email" class="col-md-4 col-form-label text-md-right">username</label>

          <div class="col-md-6">
          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

          @error('email')
          <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
          </span>
          @enderror
          </div>
          </div>

          <div class="form-group row">
          <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

          <div class="col-md-6">
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

          @error('password')
          <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
          </span>
          @enderror
          </div>
          </div>

          <div class="form-group row">
          <div class="col-md-6 offset-md-4">
          <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

          <label class="form-check-label" for="remember">
          {{ __('Remember Me') }}
          </label>
          </div>
          </div>
          </div>

          <div class="form-group row mb-0">
          <div class="col-md-8 offset-md-4">
          <button type="submit" class="btn btn-primary">
          {{ __('Login') }}
          </button>
          <a href="{{ route('register') }}" class=' '><input type='button' class='btn btn-secondary' value='Register'></a>

          </div>
          </div>
          </form>
          </div>
          </div>
          </div>
          </div>
          @if (Route::has('register'))
            <div class='text-center'>
              <a href="{{ route('register') }}" class=' fs-4'>Register</a>
            </div>

          @endif
          <h1>
            "I Don't Want To Register"
          </h1>
            <div class='p-1'>
              Unfortunately, the easiest way to implement the user system was to require registration. I've removed the email requirement so you just need a username and password. (Don't lose your password because there is no reset!)
            </div><div class='p-1'>
              I also understand that you might not really want to even do that and that's reasonable. Maybe you wanna try out the game without registering. I actually did do a javascript only version of the game, so if you want a really, really basic version that I did in a day, <a href='/old'>here it is</a>.

            </div>
          </div>

          @endauth

          @endif
          </div>
@endsection
