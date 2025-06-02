@extends('layouts.app')

@section('title', 'Редактирование спортсмена')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/athletes.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Редактирование спортсмена</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('athletes.update', $athlete->id) }}" method="POST" class="athlete-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name" class="form-label">Имя:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $athlete->name) }}" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="surname" class="form-label">Фамилия:</label>
                <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname', $athlete->surname) }}" required>
                @error('surname')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $athlete->email) }}" required>
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Телефон:</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $athlete->phone) }}" required>
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="birth" class="form-label">Дата рождения:</label>
                <input type="date" class="form-control" id="birth" name="birth" value="{{ old('birth', $athlete->birth) }}" min="1950-01-01" max="2025-12-31" required>
                @error('birth')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="group_id" class="form-label">Группа:</label>
                <select class="form-select" id="group_id" name="group_id">
                    <option value="">Выберите группу</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ $athlete->groups->contains($group->id) ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                    @endforeach
                </select>
                @error('group_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="athlete-form-buttons">
                <a href="{{ route('athletes.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>
@endsection