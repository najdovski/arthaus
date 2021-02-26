@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-12 mt-3 px-0">
      <form class="row js--validate" action="{{ route('reports') }}" method="GET">
        <div class="form-group col-12 col-md-4">
          <label for="start-date">Start date:</label>
          <input
          id="start-date"
          class="form-control"
          type="date"
          name="start-date"
          max="{{ \App\Helpers\AppHelper::formatDateTimeInput(now(), true) }}"
          value="{{ $startDate ? $startDate : '' }}"
          >
        </div>
        <div class="form-group col-12 col-md-4">
          <label for="end-date">End date:</label>
          <input
          id="end-date"
          class="form-control"
          type="date"
          name="end-date"
          max="{{ \App\Helpers\AppHelper::formatDateTimeInput(now(), true) }}"
          value="{{ $endDate ? $endDate : '' }}"
          >
        </div>
        <div class="form-group col-12 col-md-4">
          <label for="submit-filter"></label>
          <input type="submit" id="submit-filter" class="btn btn-block btn-success text-white font-weight-bold mt-md-2" value="Filter">
        </div>
      </form>
    </div>
  </div>

  <div class="row">
    @include('activities.partials.page-title', ['title' => 'Reports'])
  </div>

  @if ($startDate && $endDate && $datesWithTotalTime)
  <div class="row">
    <div class="col-12 text-center mb-2">
      Showing <span class="font-weight-bold">{{ sizeof($datesWithTotalTime) }}</span> results
      between <span class="font-weight-bold">{{ \App\Helpers\AppHelper::formatDateTime($startDate, false) }}</span>
      and <span class="font-weight-bold">{{ \App\Helpers\AppHelper::formatDateTime($endDate, false) }}</span>
      for <span class="font-weight-bold">{{ auth()->user()->name }}</span>
    </div>
  </div>
  <div class="row">
    <table class="table">
      <thead class="thead-dark text-center">
        <tr>
          <th scope="col">
           <h2>#</h2>
          </th>
          <th scope="col">
            <i class="fas fa-2x fa-calendar-check"></i> <br /> Date
          </th>
          <th scope="col">
            <i class="fas fa-2x fa-clock"></i> <br /> Total time spent on activities
          </th>
        </tr>
      </thead>
      <tbody class="text-center">
        @foreach ($datesWithTotalTime as $date => $minutes)
        <tr>
          <th>{{ $loop->iteration }}</th>
          <td>{{ \App\Helpers\AppHelper::formatDateTime($date, false) }}</td>
          <td>{{ \App\Helpers\AppHelper::minToHours($minutes) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
    <div class="row shadow mt-3 py-3">
      <div class="col-12 text-center">
        <div class="h3 font-weight-bold text-secondary">
          @if (!$startDate || !$endDate)
            Please select a date range
          @elseif (sizeof($datesWithTotalTime) < 1)
            No reports for the selected date range...
          @endif
        </div>
        <div>
          <i class="text-secondary fas fa-10x fa-file-alt"></i>
        </div>
      </div>
    </div>
  @endif
@endsection