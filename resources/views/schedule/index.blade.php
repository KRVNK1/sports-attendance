@extends('layouts.app')

@section('title', 'Расписание')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endsection

@section('content')

@auth
@if(auth()->user()->role == 'admin')
<div class="admin-info">
    <h3>Административные действия</h3>
    <div class="quick-actions">
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-primary">
            Общая статистика посещаемости
        </a>
    </div>
</div>
@endif
@endauth


<div class="schedule-header">
    <h1>Расписание</h1>
    @auth
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('schedule.create') }}" class="btn btn-primary">Добавить тренировку</a>
    @elseif(auth()->user()->role == 'coach')
    <a href="{{ route('coach.schedule.create') }}" class="btn btn-primary">Добавить тренировку</a>
    @endif
    @endauth
</div>

@if($trainingsByDay->isEmpty())
<div class="alert alert-info">
    Нет запланированных тренировок.
</div>
@else
@foreach($trainingsByDay as $dayData)
<div class="schedule-day">
    <div class="schedule-day-header">
        {{ $dayData['day_name'] }}
    </div>
    <div class="schedule-day-content">
        @foreach($dayData['trainings'] as $training)
        <div class="schedule-item">
            <div class="schedule-item-header">
                <div class="schedule-title">
                    {{ $training->group->name }}
                </div>
                <div class="schedule-status status-{{ $training->status }}">
                    @switch($training->status)
                    @case('upcoming')
                    Предстоящая
                    @break
                    @case('active')
                    Идет сейчас
                    @break
                    @case('completed')
                    Завершена
                    @break
                    @default
                    Неизвестно
                    @endswitch
                </div>
            </div>
            <div class="schedule-item-details">
                <p><strong>Время:</strong> {{ $training->start_time->format('H:i') }} - {{ $training->end_time->format('H:i') }}</p>
                <p><strong>Тренер:</strong> {{ $training->group->coach->name }} {{ $training->group->coach->surname }}</p>
                <p><strong>Место:</strong> {{ $training->location }}</p>
                @if($training->notes)
                <p><strong>Примечания:</strong> {{ $training->notes }}</p>
                @endif

                @auth
                <div class="schedule-actions">
                    <!-- Кнопки управления тренировкой  -->
                    <!-- Если авторизированный пользователь админ или тренер, и id   -->
                    @if(auth()->user()->role == 'admin' || (auth()->user()->role == 'coach' && auth()->user()->id == $training->group->coach_id))
                    <div class="management-actions">

                        <!-- Кнопки изменения статуса  -->
                        @if(auth()->user()->role == 'admin')
                        <div class="status-actions">
                            @if($training->status != 'upcoming')
                            <form action="{{ route('schedule.updateStatus', $training->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="upcoming">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Сделать предстоящей</button>
                            </form>
                            @endif

                            @if($training->status != 'active')
                            <form action="{{ route('schedule.updateStatus', $training->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-outline-success btn-sm">Сделать активной</button>
                            </form>
                            @endif

                            @if($training->status != 'completed')
                            <form action="{{ route('schedule.updateStatus', $training->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-outline-info btn-sm">Завершить</button>
                            </form>
                            @endif
                        </div>
                        @elseif(auth()->user()->role == 'coach')
                        <div class="status-actions">
                            @if($training->status != 'upcoming')
                            <form action="{{ route('coach.schedule.updateStatus', $training->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="upcoming">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Сделать предстоящей</button>
                            </form>
                            @endif

                            @if($training->status != 'active')
                            <form action="{{ route('coach.schedule.updateStatus', $training->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-outline-success btn-sm">Сделать активной</button>
                            </form>
                            @endif

                            @if($training->status != 'completed')
                            <form action="{{ route('coach.schedule.updateStatus', $training->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-outline-info btn-sm">Завершить</button>
                            </form>
                            @endif
                        </div>
                        @endif

                        @if(auth()->user()->role == 'admin')
                        <a href="{{ route('schedule.edit', $training->id) }}" class="btn btn-warning btn-sm">
                            Редактировать
                        </a>

                        <form action="{{ route('schedule.destroy', $training->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                Удалить
                            </button>
                        </form>

                        @elseif(auth()->user()->role == 'coach')
                        <a href="{{ route('coach.schedule.edit', $training->id) }}" class="btn btn-warning btn-sm">
                            Редактировать
                        </a>

                        <form action="{{ route('coach.schedule.destroy', $training->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                Удалить
                            </button>
                        </form>

                        @endif
                    </div>

                    <!-- Кнопки посещаемости -->
                    <!-- Админ -->
                    @if(auth()->user()->role == 'admin')
                    <div class="attendance-actions">
                        @if($training->status == 'active' || $training->status == 'upcoming')
                        <a href="{{ route('attendance.mark', $training->id) }}" class="btn btn-success btn-sm yellow">
                            Отметить посещаемость
                        </a>
                        @endif

                        @if($training->status == 'completed')
                        <a href="{{ route('attendance.show', $training->id) }}" class="btn btn-info btn-sm green">
                            Посмотреть посещаемость
                        </a>
                        @endif
                    </div>
                    <!-- Тренер -->
                    @elseif(auth()->user()->role == 'coach')
                    <div class="attendance-actions">
                        @if($training->status == 'active' || $training->status == 'upcoming')
                        <a href="{{ route('coach.attendance.mark', $training->id) }}" class="btn btn-success btn-sm yellow">
                            Отметить посещаемость
                        </a>
                        @endif

                        @if($training->status == 'completed')
                        <a href="{{ route('coach.attendance.show', $training->id) }}" class="btn btn-info btn-sm green">
                            Посмотреть посещаемость
                        </a>
                        @endif
                    </div>
                    @endif

                    @endif
                </div>
                @endauth
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endif


@endsection