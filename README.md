Запуск проекта:

1. Клонируем репозиторий
git clone https://github.com/perf-100/proxymonitoring_docker.git
cd proxymonitoring_docker

2. Собираем Docker контейнеры
docker compose build

3. Устанавливаем php зависимости
docker compose run composer install

4. Поднимаем контейнеры в фоне
docker compose up -d

5. Выполняем миграции базы данных
sudo docker compose run artisan migrate

6. Открываем проект в браузере
Проект доступен на http://localhost:8000

7. Создаём пользователя через форму регистрации.

После входа доступны все функции мониторинга прокси.
