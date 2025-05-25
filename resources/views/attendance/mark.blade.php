@extends('layouts.app')

@section('title', 'Отметка посещаемости')

@section('content')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
<div class="card">
    <div class="card-header">
        <h1>Отметка посещаемости</h1>
        <div class="training-info">
            <p><strong>Группа:</strong> {{ $training->group->name }}</p>
            <p><strong>Дата:</strong> {{ $training->start_time->format('d.m.Y') }}</p>
            <p><strong>Время:</strong> {{ $training->start_time->format('H:i') }} - {{ $training->end_time->format('H:i') }}</p>
            <p><strong>Место:</strong> {{ $training->location }}</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('attendance.store', $training->id) }}" method="POST">
            @csrf

            @if($athletes->count() > 0)
            <div class="attendance-list">
                <div class="form-group">
                    <label class="form-label">Отметьте присутствующих спортсменов:</label>

                    <div class="athletes-grid">
                        @foreach($athletes as $athlete)
                        <div class="athlete-item">
                            <label class="athlete-checkbox">
                                <input type="checkbox"
                                    name="attendance[{{ $athlete->id }}]"
                                    value="1"
                                    {{ isset($existingAttendance[$athlete->id]) && $existingAttendance[$athlete->id] ? 'checked' : '' }}>
                                <span class="athlete-name">
                                    {{ $athlete->name }} {{ $athlete->surname }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-buttons">
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Сохранить посещаемость</button>
            </div>
            @else
            <div class="alert alert-warning">
                В этой группе нет спортсменов.
            </div>
            <div class="form-buttons">
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Назад</a>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection