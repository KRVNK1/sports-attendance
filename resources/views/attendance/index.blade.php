@extends('layouts.app')

@section('title', 'Статистика посещаемости')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-header">
    <h1>Общая статистика посещаемости</h1>
</div>

<div class="attendance-overview">
    <div class="stats-cards">
        <div class="stat-card">
            <h3>Всего групп</h3>
            <div class="stat-number">{{ $groups->count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Всего тренировок</h3>
            <div class="stat-number">{{ $groups->sum('trainings_count') }}</div>
        </div>
        <div class="stat-card">
            <h3>Активных спортсменов</h3>
            <div class="stat-number">{{ $groups->sum(function($group) { return $group->athletes->count(); }) }}</div>
        </div>
    </div>
</div>

<div class="groups-attendance">
    <h2>Статистика по группам</h2>

    @if($groups->isEmpty())
    <div class="alert alert-info">
        Нет данных для отображения.
    </div>
    @else
    <div class="groups-grid">
        @foreach($groups as $group)
        <div class="group-card">
            <div class="group-header">
                <h3>{{ $group->name }}</h3>
                <span class="group-coach">Тренер: {{ $group->coach->name }} {{ $group->coach->surname }}</span>
            </div>

            <div class="group-stats">
                <div class="stat-item">
                    <span class="stat-label">Спортсменов:</span>
                    <span class="stat-value">{{ $group->athletes->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Всего тренировок:</span>
                    <span class="stat-value">{{ $group->trainings_count }}</span>
                </div>
            </div>

            <div class="group-actions">
                <a href="{{ route('attendance.group', $group->id) }}" class="btn btn-primary btn-sm">
                    Посещаемость группы
                </a>
                <a href="{{ route('groups.show', $group->id) }}" class="btn btn-outline-secondary btn-sm">
                    Информация о группе
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection