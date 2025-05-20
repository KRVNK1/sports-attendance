<!-- resources/views/coaches/index.blade.php -->
@extends('layouts.app')

@section('title', 'Тренеры')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Тренеры</h1>
    <a href="{{ route('coaches.create') }}" class="btn btn-primary">Добавить тренера</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Телефон</th>
                <th>Дата рождения</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coaches as $coach)
            <tr>
                <td>{{ $coach->name }}</td>
                <td>{{ $coach->surname }}</td>
                <td>{{ $coach->phone }}</td>
                <td>{{ $coach->birth }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('coaches.edit', $coach->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('coaches.destroy', $coach->id) }}" method="POST" class="d-inline">
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