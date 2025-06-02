## Установка

composer install
cp .env.example .env
php artisan key:generate

для учебы
DB_CONNECTION=mysql
DB_HOST=web.edu
DB_PORT=3306
DB_DATABASE=название бд
DB_USERNAME=учетка
DB_PASSWORD=пароль учетки

для дома
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

php artisan migrate:fresh --seed

## Запуск

php artisan serve