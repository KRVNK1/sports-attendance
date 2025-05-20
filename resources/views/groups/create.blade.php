@extends('layouts.app')

@section('title', 'Создание группы')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/groups.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Создание группы</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('groups.store') }}" method="POST" class="group-form">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Название группы:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Описание:</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="coach_id" class="form-label">Тренер:</label>
                <select class="form-select" id="coach_id" name="coach_id" required>
                    <option value="">Выберите тренера</option>
                    @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>
                        {{ $coach->name }} {{ $coach->surname }}
                    </option>
                    @endforeach
                </select>
                @error('coach_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="group-form-buttons">
                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Создать</button>
            </div>
        </form>
    </div>
</div>
@endsection