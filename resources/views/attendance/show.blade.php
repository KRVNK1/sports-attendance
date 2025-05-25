@extends('layouts.app')

@section('title', 'Посещаемость тренировки')

@section('content')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
<div class="card">
    <div class="card-header">
        <h1>Посещаемость тренировки</h1>
        <div class="training-info">
            <p><strong>Группа:</strong> {{ $training->group->name }}</p>
            <p><strong>Дата:</strong> {{ $training->start_time->format('d.m.Y') }}</p>
            <p><strong>Время:</strong> {{ $training->start_time->format('H:i') }} - {{ $training->end_time->format('H:i') }}</p>
            <p><strong>Место:</strong> {{ $training->location }}</p>
            <p><strong>Статус:</strong>
                <span class="status-badge status-{{ $training->status }}">
                    @switch($training->status)
                    @case('active') Активна @break
                    @case('completed') Завершена @break
                    @case('cancelled') Отменена @break
                    @endswitch
                </span>
            </p>
        </div>
    </div>
    <div class="card-body">
        @if($attendances->count() > 0)
        <div class="attendance-summary">
            <div class="summary-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $attendances->where('present', true)->count() }}</span>
                    <span class="stat-label">Присутствовали</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $attendances->where('present', false)->count() }}</span>
                    <span class="stat-label">Отсутствовали</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $attendances->count() }}</span>
                    <span class="stat-label">Всего спортсменов</span>
                </div>
            </div>
        </div>

        <div class="attendance-details">
            <h3>Детальная информация</h3>
            <div class="athletes-list">
                @foreach($attendances as $attendance)
                <div class="athlete-row {{ $attendance->present ? 'present' : 'absent' }}">
                    <div class="athlete-info">
                        <span class="athlete-name">
                            {{ $attendance->athlete->surname }} {{ $attendance->athlete->name }}
                        </span>
                    </div>
                    <div class="attendance-status">
                        @if($attendance->present)
                        <span class="status-present">✓ Присутствовал</span>
                        @else
                        <span class="status-absent">✗ Отсутствовал</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="alert alert-info">
            Посещаемость для этой тренировки еще не отмечена.
        </div>
        @endif

        <div class="form-buttons">
            <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Назад к расписанию</a>
            @if($training->status !== 'cancelled' && (Auth::user()->isAdmin() || (Auth::user()->isCoach() && $training->group->coach_id === Auth::user()->id)))
            <a href="{{ route('attendance.mark', $training->id) }}" class="btn btn-primary">
                {{ $attendances->count() > 0 ? 'Изменить посещаемость' : 'Отметить посещаемость' }}
            </a>
            @endif
        </div>
    </div>
</div>
@endsection