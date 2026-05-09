@extends('layouts.app')

@section('title', 'Моя активность - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-history text-info me-2"></i>
            Моя активность
        </h1>
        <a href="{{ route('activity.feed') }}" class="btn btn-outline-secondary">
            <i class="fas fa-rss me-2"></i>Лента друзей
        </a>
    </div>

    @if($activities->count() > 0)
        <div class="timeline">
            @foreach($activities as $activity)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle fa-3x text-secondary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="mb-1">
                                    {!! \App\Services\ActivityService::getMessage($activity) !!}
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-history fa-4x mb-3"></i>
            <p>Пока нет активности</p>
        </div>
    @endif
</div>
@endsection
