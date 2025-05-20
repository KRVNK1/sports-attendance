<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-page">
    <div class="auth-container">

        <div class="auth-form">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="auth-header">
                    <h2>Авторизуйтесь</h2>
                </div>

                <div class="auth-form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-form-check">
                    <input class="auth-form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Запомнить меня
                    </label>
                </div>

                <div class="auth-form-group">
                    <button type="submit" class="btn btn-primary auth-btn-block">
                        Войти
                    </button>
                </div>

            </form>
        </div>
    </div>
</body>

</html>