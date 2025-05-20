<!-- resources/views/athletes/index.blade.php -->
@extends('layouts.app')

@section('title', 'Спортсмены')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Спортсмены</h1>
    <a href="{{ route('athletes.create') }}" class="btn btn-primary">Добавить спортсмена</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Телефон</th>
                <th>Дата рождения</th>
                <th>Группа</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($athletes as $athlete)
            <tr>
                <td>{{ $athlete->name }}</td>
                <td>{{ $athlete->surname }}</td>
                <td>{{ $athlete->phone }}</td>
                <td>{{ $athlete->birth }}</td>
                <td>
                    @foreach ($athlete->groups as $group)
                    <span class="badge bg-info">{{ $group->name }}</span><br>
                    @endforeach
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('athletes.edit', $athlete->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('athletes.destroy', $athlete->id) }}" method="POST" class="d-inline">
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