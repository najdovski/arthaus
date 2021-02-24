@extends('layouts.app')

@section('content')
  <div class="row justify-content-end mb-3">
    <div class="col-auto">
      <a href="{{ route('create-activity') }}" class="btn btn-block btn-success text-white font-weight-bold">
        <i class="fas fa-plus mr-2"></i> New activity
      </a>
    </div>
  </div>

  @if (sizeof($activities) > 0)
    <div class="row justify-content-center justify-content-md-start px-3 activities">
      @include('activities.partials.page-title', ['title' => 'List of activities'])
      @foreach ($activities as $activity)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 px-4 py-2">
          <div class="row single-activity activity-{{ rand(0, 3) }} text-white rounded shadow h-100 p-3">
            <div class="col-12 text-right pb-2 px-0 mx-0">
              <a class="text-white" href="{{ route('edit-activity', ['id' => $activity->id]) }}"><i class="fas fa-edit border-right pr-1"></i></a>
              <i type="button" class="fas fa-trash" data-toggle="modal" data-target="#remove-activity-{{ $activity->id }}"></i>
            </div>
            <div class="col-12 font-weight-bold pb-2 text-break">
              {{ \App\Helpers\AppHelper::trimString($activity->description) }}
            </div>
            <div class="col-12 border-top pt-3 pb-2">
              <div class="row">
                <div class="text-center {{ \App\Helpers\AppHelper::formatDate($activity->started_at) !== \App\Helpers\AppHelper::formatDate($activity->finished_at) ? 'col-6' : 'col-12' }}">
                  <div class="font-weight-bold"><i class="fas fa-2x fa-calendar-check"></i></div>
                  <span class="small">{{ \App\Helpers\AppHelper::formatDate($activity->started_at) }}</span>
                </div>
                @if (\App\Helpers\AppHelper::formatDate($activity->started_at) !== \App\Helpers\AppHelper::formatDate($activity->finished_at))
                  <div class="col-6 text-center">
                    <div class="font-weight-bold"><i class="fas fa-2x fa-calendar-times"></i></div>
                    <span class="small">{{ \App\Helpers\AppHelper::formatDate($activity->finished_at) }}</span>
                  </div>
                @endif
              </div>
            </div>
            <div class="col-12 border-top pt-3 pb-2">
              <div class="row no-gutters">
                <div class="pr-1 col-6 text-right align-self-center">
                  <i class="fas fa-2x fa-clock"></i>
                </div>
                <div class="pl-1 col-6 align-self-center font-weight-bold">
                  <div>{{ \App\Helpers\AppHelper::timeDiff($activity->started_at, $activity->finished_at) }}</div>
                </div>
              </div>
            </div>
            <div class="col-12 border-top pt-3 text-right small font-italic">
              by {{ $activity->user->name }}
            </div>
          </div>
        </div>
        @include('activities.partials.modal-remove-confirmation', ['id' => $activity->id])
      @endforeach
    </div>

    <div class="row mt-4 justify-content-center">
      <div class="col-auto">
        {{ $activities->links('pagination::bootstrap-4') }}
      </div>
    </div>
  @else
    <div class="row shadow py-2">
      <div class="col-12 text-center">
        <div class="h3 font-weight-bold text-secondary">No activities to show...</div>
        <div>
          <i class="text-secondary fas fa-10x fa-folder-open"></i>
        </div>
      </div>
    </div>
  @endif
@endsection
