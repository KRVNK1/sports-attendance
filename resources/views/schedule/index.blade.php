@extends('layouts.app')

@section('title', 'Расписание')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endsection

@section('content')
<div class="schedule-header">
    <h1>Расписание</h1>
    @auth
    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'coach')
    <a href="{{ route('schedule.create') }}" class="btn btn-primary">Добавить тренировку</a>
    @endif
    @endauth
</div>

@if($trainingsByDay->isEmpty())
<div class="alert alert-info">
    Нет запланированных тренировок.
</div>
@else
@foreach($trainingsByDay as $day => $trainings)
<div class="schedule-day">
    <div class="schedule-day-header">
        {{ $day }}
        
    </div>
    <div class="schedule-day-content">
        @foreach($trainings as $training)
        <div class="schedule-item">
            <div class="schedule-item-header">
                {{ $training->group->name }}
            </div>
            <div class="schedule-item-details">
                <p><strong>Время:</strong> {{ $training->start_time->format('H:i') }} - {{ $training->end_time->format('H:i') }}</p>
                <p><strong>Тренер:</strong> {{ $training->group->coach->name }} {{ $training->group->coach->surname }}</p>
                <p><strong>Место:</strong> {{ $training->location }}</p>
                @if($training->notes)
                <p><strong>Примечания:</strong> {{ $training->notes }}</p>
                @endif

                @auth
                @if(auth()->user()->role == 'admin' || (auth()->user()->role == 'coach' && auth()->user()->id == $training->group->coach_id))
                <div class="schedule-actions">
                    <a href="{{ route('schedule.edit', $training->id) }}" class="btn btn-warning">Редактировать</a>
                    <form action="{{ route('schedule.destroy', $training->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </div>
                @endif
                @endauth
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endif
@endsection