@extends('layouts.app')

@section('content')
  <div class="row">
    @include('activities.partials.page-title', [
      'title' => isset($activity) ? 'Edit activity' : 'Create new activity'
    ])
  </div>
  @if (\App\Helpers\ActivitiesHelper::getUnavailableDateRanges(isset($activity) ? $activity->id : null))
  <div class="row">
    <div class="col-12 px-0">
      <button class="btn btn-danger font-weight-bold" data-toggle="collapse" href="#collapseExample" role="button">
        Expand unavailable date ranges
      </button>
    </div>
    <div class="col-12 collapse" id="collapseExample">
      <div class="row mt-2">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">From</th>
              <th scope="col">To</th>
            </tr>
          </thead>
          <tbody>
            @foreach (\App\Helpers\ActivitiesHelper::getUnavailableDateRanges(isset($activity) ? $activity->id : null) as $date)
            <tr>
              <th>{{ $loop->iteration }}</th>
              <td>{{ \App\Helpers\AppHelper::formatDateTime($date['from']) }}</td>
              <td>{{ \App\Helpers\AppHelper::formatDateTime($date['to']) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif
  <form
    id="activity-form"
    class="row shadow rounded my-2 p-3 js--validate"
    action="{{ isset($activity) ? route('update-activity') : route('store-activity') }}"
    method="POST">
    @csrf
    @isset ($activity)
      @method('put')
      <input type="hidden" name="activity-id" value="{{ $activity->id }}">
    @endisset
    
    <div class="col-12 col-md-6 form-group">
      <label for="started-at">Started at:</label>
      <input
        id="started-at"
        class="form-control"
        type="datetime-local"
        name="started-at"
        max="{{ \App\Helpers\AppHelper::formatDateTimeInput(now(), true) }}"
        value="{{ old('started-at') ? old('started-at') : (isset($activity->started_at) ? \App\Helpers\AppHelper::formatDateTimeInput($activity->started_at) : '') }}"
        data-disabled-dates="{{ \App\Helpers\ActivitiesHelper::getDisabledDates(isset($activity) ? $activity->id : null) }}"
        required
        >
    </div>

    <div class="col-12 col-md-6 form-group">
      <label for="finished-at">Finished at:</label>
      <input
        id="finished-at"
        class="form-control"
        type="datetime-local"
        name="finished-at"
        min="{{ 
        old('started-at') ? old('started-at') :
        (isset($activity->started_at) 
        ? \App\Helpers\AppHelper::formatDateTimeInput($activity->started_at) 
        : \App\Helpers\AppHelper::formatDateTimeInput(now(), true)) }}"
        max="{{ \App\Helpers\AppHelper::formatDateTimeInput(now(), true) }}"
        value="{{
        old('finished-at') ? old('finished-at')
        : (isset($activity->finished_at)
        ? \App\Helpers\AppHelper::formatDateTimeInput($activity->finished_at) : '') }}"
        required>
    </div>

    <div class="col-12 form-group">
      <label for="description">Description:</label>
      <textarea id="description" class="form-control" name="description" rows="4" minlength="10" maxlength="500" required>
        @if (old('description'))
          {{ old('description') }}
        @elseif (isset($activity->description) && $activity->description)
          {{ $activity->description }}
        @endif
      </textarea>
    </div>

  </form>
  <div class="row justify-content-end mt-3">
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 pl-0">
      <a href="{{ url()->previous() }}" class="btn btn-block btn-danger text-white font-weight-bold">Back</a>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 pr-0">
      <button type="submit" form="activity-form" class="btn btn-block btn-success text-white font-weight-bold">Submit</button>
    </div>
  </div>
@endsection
