<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-page">
    <div class="auth-container">

        <div class="auth-form">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="auth-header">
                    <h2>Зарегистрируйтесь</h2>
                </div>
                
                <div class="auth-form-group">
                    <label for="name" class="form-label">Имя</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="surname" class="form-label">Фамилия</label>
                    <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autocomplete="surname">
                    @error('surname')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="phone" class="form-label">Телефон</label>
                    <input id="phone" type="tel" class="form-control" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="birth" class="form-label">Дата рождения</label>
                    <input id="birth" type="date" class="form-control" name="birth" value="{{ old('birth') }}" required>
                    @error('birth')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="password-confirm" class="form-label">Подтверждение пароля</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="auth-form-group">
                    <button type="submit" class="btn btn-primary auth-btn-block">
                        Зарегистрироваться
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>