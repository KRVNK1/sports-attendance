@extends('layouts.app')

@section('title', 'Редактирование тренировки')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Редактирование тренировки</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('schedule.update', $training->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="group_id" class="form-label">Группа:</label>
                <select class="form-select" id="group_id" name="group_id" required>
                    <option value="">Выберите группу</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id', $training->group_id) == $group->id ? 'selected' : '' }}>
                        {{ $group->name }} (Тренер: {{ $group->coach->name }} {{ $group->coach->surname }})
                    </option>
                    @endforeach
                </select>
                @error('group_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_time" class="form-label">Дата и время начала:</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                    value="{{ old('start_time', $training->start_time->format('Y-m-d\TH:i')) }}" required>
                @error('start_time')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_time" class="form-label">Дата и время окончания:</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                    value="{{ old('end_time', $training->end_time->format('Y-m-d\TH:i')) }}" required>
                @error('end_time')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="location" class="form-label">Место проведения:</label>
                <input type="text" class="form-control" id="location" name="location"
                    value="{{ old('location', $training->location) }}" required>
                @error('location')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Примечания:</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $training->notes) }}</textarea>
                @error('notes')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Статус:</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" {{ old('status', $training->status) == 'active' ? 'selected' : '' }}>Активна</option>
                    <option value="completed" {{ old('status', $training->status) == 'completed' ? 'selected' : '' }}>Завершена</option>
                    <option value="cancelled" {{ old('status', $training->status) == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                </select>
                @error('status')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-buttons">
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>
@endsection