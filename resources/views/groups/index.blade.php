<!-- resources/views/groups/index.blade.php -->
@extends('layouts.app')

@section('title', 'Группы')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Группы</h1>
    <a href="{{ route('groups.create') }}" class="btn btn-primary">Добавить группу</a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Расписание по дням недели
            </div>
            <div class="card-body">
                <div class="accordion" id="scheduleAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#monday">
                                Понедельник
                            </button>
                        </h2>
                        <div id="monday" class="accordion-collapse collapse show" data-bs-parent="#scheduleAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Название группы</h5>
                                                <p class="card-text">Время: 18:00 - 19:30</p>
                                                <p class="card-text">Тренер: Иванов И.И.</p>
                                                <p class="card-text">Зал: 1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Другие группы понедельника -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#wednesday">
                                Среда
                            </button>
                        </h2>
                        <div id="wednesday" class="accordion-collapse collapse" data-bs-parent="#scheduleAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Название группы</h5>
                                                <p class="card-text">Время: 18:00 - 19:30</p>
                                                <p class="card-text">Тренер: Иванов И.И.</p>
                                                <p class="card-text">Зал: 1</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#saturday">
                                Суббота
                            </button>
                        </h2>
                        <div id="saturday" class="accordion-collapse collapse" data-bs-parent="#scheduleAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Название группы</h5>
                                                <p class="card-text">Время: 10:00 - 11:30</p>
                                                <p class="card-text">Тренер: Петров П.П.</p>
                                                <p class="card-text">Зал: 2</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Название</th>
                <th>Тренер</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->coach->name ?? '' }} {{ $group->coach->surname ?? '' }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection