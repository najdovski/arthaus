@extends('layouts.app')

@section('content')
  <div class="row justify-content-center">
    @guest
      @include('partials.welcome-card', [
        'title' => 'Login',
        'bg' => 'bg-success',
        'icon' => 'fas fa-sign-in-alt',
        'url' => route('login')
      ])
      @if (Route::has('register'))
        @include('partials.welcome-card', [
          'title' => 'Register',
          'bg' => 'bg-info',
          'icon' => 'fas fa-user',
          'url' => route('register')
        ])
      @endif
    @endguest

    @auth
      @include('partials.welcome-card', [
        'title' => 'Activities',
        'bg' => 'bg-success',
        'icon' => 'fas fa-clipboard',
        'url' => route('activities')
      ])
      @include('partials.welcome-card', [
        'title' => 'Reports',
        'bg' => 'bg-info',
        'icon' => 'fas fa-file-alt',
        'url' => route('reports')
      ])
    @endauth
  </div>
@endsection