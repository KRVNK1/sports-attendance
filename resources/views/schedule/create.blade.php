@extends('layouts.app')

@section('title', 'Добавление тренировки')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Добавление тренировки</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('schedule.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="group_id" class="form-label">Группа:</label>
                <select class="form-select" id="group_id" name="group_id" required>
                    <option value="">Выберите группу</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }} (Тренер: {{ $group->coach->name }} {{ $group->coach->surname }})
                    </option>
                    @endforeach
                </select>
                @error('group_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="day_of_week" class="form-label">День недели:</label>
                <select class="form-select" id="day_of_week" name="day_of_week" required>
                    <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>Понедельник</option>
                    <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Вторник</option>
                    <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Среда</option>
                    <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Четверг</option>
                    <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Пятница</option>
                    <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Суббота</option>
                    <option value="7" {{ old('day_of_week') == 7 ? 'selected' : '' }}>Воскресенье</option>
                </select>
                @error('day_of_week')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_time" class="form-label">Время начала:</label>
                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                @error('start_time')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_time" class="form-label">Время окончания:</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                @error('end_time')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="location" class="form-label">Место проведения:</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                @error('location')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Примечания:</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="recurring" name="recurring" value="1" {{ old('recurring') ? 'checked' : '' }}>
                    <label class="form-check-label" for="recurring">
                        Повторять еженедельно
                    </label>
                </div>
            </div>

            <div class="form-group" id="weeks_count_group" style="{{ old('recurring') ? '' : 'display: none;' }}">
                <label for="weeks_count" class="form-label">Количество недель:</label>
                <input type="number" class="form-control" id="weeks_count" name="weeks_count" value="{{ old('weeks_count', 4) }}" min="1" max="12">
                @error('weeks_count')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-buttons">
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const recurringCheckbox = document.getElementById('recurring');
    const weeksCountGroup = document.getElementById('weeks_count_group');

    recurringCheckbox.addEventListener('change', function() {
        if (this.checked) {
            weeksCountGroup.style.display = 'block';
        } else {
            weeksCountGroup.style.display = 'none';
        }
    });
</script>
@endsection