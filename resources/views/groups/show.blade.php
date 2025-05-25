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
    <div class="trainings-section">
        <h3>Расписание тренировок</h3>
        @if($group->trainings->count() > 0)
        <div class="trainings-list">
            @foreach($group->trainings->sortBy('start_time') as $training)
            <div class="training-card">
                <div class="training-header">
                    <div class="training-date">
                        {{ $training->start_time->format('d.m.Y') }} ({{ $training->dayOfWeek }})
                    </div>
                    <div class="training-status status-{{ $training->status }}">
                        @switch($training->status)
                        @case('upcoming')
                        Предстоящая
                        @break
                        @case('active')
                        Активная
                        @break
                        @case('completed')
                        Завершена
                        @break
                        @default
                        Неизвестно
                        @endswitch
                    </div>
                </div>
                <div class="training-details">
                    <p><strong>Время:</strong> {{ $training->start_time->format('H:i') }} - {{ $training->end_time->format('H:i') }}</p>
                    <p><strong>Место:</strong> {{ $training->location }}</p>
                    @if($training->notes)
                    <p><strong>Примечания:</strong> {{ $training->notes }}</p>
                    @endif
                </div>
                @auth
                @if(auth()->user()->role == 'admin' || (auth()->user()->role == 'coach' && auth()->user()->id == $group->coach_id))
                <div class="training-actions">
                    <a href="{{ route('schedule.edit', $training->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                    @if($training->status == 'active' || $training->status == 'upcoming')
                    <a href="{{ route('attendance.mark', $training->id) }}" class="btn btn-sm btn-success">Отметить посещаемость</a>
                    @endif
                    @if($training->status == 'completed')
                    <a href="{{ route('attendance.show', $training->id) }}" class="btn btn-sm btn-info">Посмотреть посещаемость</a>
                    @endif
                </div>
                @endif
                @endauth
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            Для этой группы пока нет запланированных тренировок.
        </div>
        @endif
    </div>

</div>
@endsection