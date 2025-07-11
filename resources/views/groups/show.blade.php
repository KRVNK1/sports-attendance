@extends('layouts.app')

@section('title', 'Информация о группе')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/groups.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="group-header">
        <h1>{{ $group->name }}</h1>
        <div class="group-actions">
            @auth
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning">Редактировать</a>
            <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
            </form>
            @endif
            @endauth
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Назад к списку</a>
        </div>
    </div>

    <!-- Информация о группе -->
    <div class="group-info-card">
        <h3>Основная информация</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Название:</strong>
                <span>{{ $group->name }}</span>
            </div>
            <div class="info-item">
                <strong>Описание:</strong>
                <span>{{ $group->description ?? 'Не указано' }}</span>
            </div>
            <div class="info-item">
                <strong>Тренер:</strong>
                <span>{{ $group->coach->name }} {{ $group->coach->surname }}</span>
            </div>
            <div class="info-item">
                <strong>Количество спортсменов:</strong>
                <span>{{ $group->athletes->count() }} человек</span>
            </div>
        </div>
    </div>

    <!-- Список спортсменов -->
    <div class="athletes-section">
        <h3>Спортсмены группы</h3>
        @if($group->athletes->count() > 0)
        <div class="athletes-grid">
            @foreach($group->athletes as $athlete)
            <div class="athlete-card">
                <div class="athlete-info">
                    <h4>{{ $athlete->name }} {{ $athlete->surname }}</h4>
                    <p><strong>Email:</strong> {{ $athlete->email }}</p>
                    <p><strong>Телефон:</strong> {{ $athlete->phone }}</p>
                    <p><strong>Дата рождения:</strong> {{ \Carbon\Carbon::parse($athlete->birth)->format('d.m.Y') }}</p>
                    <p><strong>Возраст:</strong> {{ \Carbon\Carbon::parse($athlete->birth)->age }} лет</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            В этой группе пока нет спортсменов.
        </div>
        @endif
    </div>

    <!-- Расписание тренировок -->
    
    <div class="card-body">
        <h3>Расписание тренировок</h3>

        @if($group->trainings->count() > 0)
        <div class="trainings-list">
            @foreach($group->trainings as $training)
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