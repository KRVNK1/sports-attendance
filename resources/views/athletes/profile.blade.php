@extends('layouts.app')

@section('title', 'Мой профиль')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/athletes.css') }}">
@endsection

@section('content')
<h1>Мой профиль</h1>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $athlete->name }} {{ $athlete->surname }}</h5>
        <div class="athlete-info">
            <p><strong>Email:</strong> {{ $athlete->email }}</p>
            <p><strong>Телефон:</strong> {{ $athlete->phone }}</p>
            <p><strong>Дата рождения:</strong> {{ $athlete->birth }}</p>
        </div>

        <div class="athlete-groups">
            <h6>Мои группы:</h6>
            @if($athlete->groups->count() > 0)
            <ul class="athlete-groups-list">
                @foreach($athlete->groups as $group)
                <li class="athlete-groups-item">{{ $group->name }}</li>
                @endforeach
            </ul>
            @else
            <p>Вы не состоите ни в одной группе</p>
            @endif
        </div>

        <div class="athlete-attendance">
            <h6>Мои тренировки:</h6>
            @if($athlete->attendances->count() > 0)
            <table class="athlete-attendance-table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Группа</th>
                        <th>Присутствие</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($athlete->attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->training->start_time->format('d.m.Y H:i') }}</td>
                        <td>{{ $attendance->training->group->name }}</td>
                        <td>
                            @if($attendance->present)
                            <span class="badge badge-success athlete-attendance-present">Присутствовал</span>
                            @else
                            <span class="badge badge-danger athlete-attendance-absent">Отсутствовал</span>
                            @if($attendance->absence_reason)
                            <small>({{ $attendance->absence_reason }})</small>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>У вас нет записей о посещениях</p>
            @endif
        </div>
    </div>
</div>
@endsection