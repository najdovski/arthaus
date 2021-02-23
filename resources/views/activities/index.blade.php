@extends('layouts.app')

@section('content')
  @if (sizeof($activities) > 0)
    <div class="row justify-content-center px-3">
      @foreach ($activities as $activity)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center bg-success text-white rounded shadow m-3">
          {{ $activity->description }}
        </div>
      @endforeach
    </div>
  @else
    <div class="row">
      <div class="col-12 text-center">
        You have no activities at the moment
      </div>
    </div>
  @endif
@endsection
