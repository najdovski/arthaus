@extends('layouts.app')

@section('content')
  @if ($activity)
    <div class="row shadow rounded mx-3">
      <div class="col-12 border-bottom border-dark py-4 px-5 text-center">
        <div class="font-weight-bold h5">
          <span class="border-bottom border-dark">
            Description
          </span>
        </div>
        <div class="mt-4">
          {{ $activity->description }}
        </div>
      </div>
      <div class="col-12 col-md-4 py-4 px-5">
        <div class="font-weight-bold h5 text-center">
          <span class="border-bottom border-dark">
            Start/Finish
          </span>
        </div>
        <div class="row mt-4">
          <div class="text-center col-6">
            <div class="font-weight-bold"><i class="fas fa-2x fa-calendar-check"></i></div>
            <span class="font-weight-bold">
              {{ \App\Helpers\AppHelper::formatDateTime($activity->started_at) }}
            </span>
          </div>
          <div class="col-6 text-center">
            <div class="font-weight-bold"><i class="fas fa-2x fa-calendar-times"></i></div>
            <span class="font-weight-bold">
              {{ \App\Helpers\AppHelper::formatDateTime($activity->finished_at) }}
            </span>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 py-4 px-5">
        <div class="font-weight-bold h5 text-center">
          <span class="border-bottom border-dark">
            Time spent
          </span>
        </div>
        <div class="row no-gutters mt-4">
          <div class="col-12 text-center">
            <i class="fas fa-2x fa-clock"></i>
          </div>
          <div class="col-12 text-center font-weight-bold">
            <div>{{ \App\Helpers\AppHelper::timeDiff($activity->started_at, $activity->finished_at) }}</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 py-4 px-5 text-center">
        <div class="font-weight-bold h5">
          <span class="border-bottom border-dark">
            Created by
          </span>
        </div>
        <div class="row no-gutters mt-4">
          <div class="col-12">
            <i class="fas fa-2x fa-user"></i>
          </div>
          <div class="col-12 font-weight-bold">
            {{ $activity->user->name }}
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 mx-3">
      <div class="col-12 col-sm-5 col-md-4 col-lg-3 px-0">
        <a href="{{ url()->previous() }}" class="btn btn-block btn-success text-white">Back</a>
      </div>
    </div>
  @endif
@endsection
