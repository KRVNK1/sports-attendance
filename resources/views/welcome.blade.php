<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Расписание')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Расписание</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Название</th>
                <th>Время</th>
                <th>Тренер</th>
                <th>Зал</th>
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
</div>
@endsection