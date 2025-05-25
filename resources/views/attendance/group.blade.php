@extends('layouts.app')

@section('title', 'Посещаемость группы')

@section('content')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
<div class="card">
    <div class="card-header">
        <h1>Посещаемость группы "{{ $group->name }}"</h1>
        <div class="group-info">
            <p><strong>Тренер:</strong> {{ $group->coach->name }} {{ $group->coach->surname }}</p>
            <p><strong>Количество спортсменов:</strong> {{ $athletes->count() }}</p>
            <p><strong>Проведено тренировок:</strong> {{ $trainings->count() }}</p>
        </div>
    </div>
    <div class="card-body">
        @if($trainings->count() > 0)
        <div class="trainings-list">
            @foreach($trainings as $training)
            <div class="training-item">
                <div class="training-header">
                    <h4>{{ $training->start_time->format('d.m.Y') }} ({{ $training->dayOfWeek }})</h4>
                    <span class="training-time">{{ $training->start_time->format('H:i') }} - {{ $training->end_time->format('H:i') }}</span>
                </div>

                @if($training->attendances->count() > 0)
                <div class="attendance-summary">
                    <div class="summary-numbers">
                        <span class="present-count">
                            Присутствовали: {{ $training->attendances->where('present', true)->count() }}
                        </span>
                        <span class="absent-count">
                            Отсутствовали: {{ $training->attendances->where('present', false)->count() }}
                        </span>
                    </div>
                    <a href="{{ route('attendance.show', $training->id) }}" class="btn btn-sm btn-outline">
                        Подробнее
                    </a>
                </div>

                <div class="athletes-attendance">
                    @foreach($training->attendances as $attendance)
                    <span class="athlete-badge {{ $attendance->present ? 'present' : 'absent' }}">
                        {{ $attendance->athlete->surname }} {{ $attendance->athlete->name }}
                    </span>
                    @endforeach
                </div>
                @else
                <div class="no-attendance">
                    <span class="text-muted">Посещаемость не отмечена</span>
                    @if(Auth::user()->isAdmin() || (Auth::user()->isCoach() && $group->coach_id === Auth::user()->id))
                    <a href="{{ route('attendance.mark', $training->id) }}" class="btn btn-sm btn-primary">
                        Отметить посещаемость
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            У этой группы пока нет завершенных тренировок.
        </div>
        @endif

        <div class="form-buttons">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Назад к группам</a>
        </div>
    </div>
</div>
@endsection