Запуск проекта:

<h2>1. Клонируем репозиторий</h2>
git clone https://github.com/perf-100/proxymonitoring_docker.git <br>
cd proxymonitoring_docker

<h2>2. Собираем Docker контейнеры</h2>
docker compose build

<h2>3. Устанавливаем php зависимости</h2>
docker compose run composer install

<h2>4. Поднимаем контейнеры в фоне</h2>
docker compose up -d

<h2>5. Выполняем миграции базы данных</h2>
docker compose run artisan migrate

<h2>6. Открываем проект в браузере</h2>
Проект доступен на http://localhost:8000

<h2>7. Создаём пользователя через форму регистрации.</h2>

После входа доступны все функции мониторинга прокси.
