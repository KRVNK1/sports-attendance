<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Система учета спортсменов')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile-nav.css') }}">
    @yield('styles')
</head>

<body>
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="{{ route('welcome') }}" class="navbar-brand">Система учета</a>

            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="mobile-menu" id="mobileMenu">
                <ul class="navbar-nav">
                    @auth
                    @if(auth()->user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ route('athletes.index') }}" class="nav-link">Спортсмены</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('coaches.index') }}" class="nav-link">Тренеры</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('groups.index') }}" class="nav-link">Группы</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedule.index') }}" class="nav-link">Расписание</a>
                    </li>
                    @elseif(auth()->user()->role == 'coach')
                    <li class="nav-item">
                        <a href="{{ route('coach.athletes.index') }}" class="nav-link">Спортсмены</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('coach.groups.index') }}" class="nav-link">Группы</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedule.index') }}" class="nav-link">Расписание</a>
                    </li>
                    @elseif(auth()->user()->role == 'athlete')
                    <li class="nav-item">
                        <a href="{{ route('profile') }}" class="nav-link">Мой профиль</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedule.index') }}" class="nav-link">Расписание</a>
                    </li>
                    @endif
                    @endauth
                </ul>

                <ul class="navbar-nav navbar-right">
                    @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Вход</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">Регистрация</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <span class="nav-link">{{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Выход
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuToggle.addEventListener('click', function() {
            const isActive = mobileMenu.classList.contains('active');

            if (isActive) {
                // Закрываем меню
                mobileMenu.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                document.body.style.overflow = 'auto';
            } else {
                // Открываем меню
                mobileMenu.classList.add('active');
                mobileMenuToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });

        // Закрытие меню при клике на ссылку
        const navLinks = mobileMenu.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });
    </script>
</body>

</html>