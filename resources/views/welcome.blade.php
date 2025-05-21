@extends('layouts.app')

@section('title', 'Расписание')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endsection

@section('content')
<div class="schedule-header">
    <h1>Расписание</h1>
</div>


<!-- Расписание по дням -->
<table class="schedule-table">
    <thead>
        <tr>
            <th>Группа</th>
            <th>Время</th>
            <th>Тренер</th>
            <th>Место встречи</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trainings as $training)
        <tr>
            <td>{{ $training->name ?? $training->group->name ?? 'Без названия' }}</td>
            <td>{{ $training->start_time->format('d.m.Y H:i') }} - {{ $training->end_time->format('H:i') }}</td>
            <td>{{ $training->coach->name ?? '' }} {{ $training->coach->surname ?? '' }}</td>
            <td>{{ $training->location }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection