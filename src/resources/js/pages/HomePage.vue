<template>
    <section class="info">
        <div class="info__wrapper container card">

            <h2>Сервис мониторинга прокси</h2>

            <div class="info__grid">

                <div class="info__item">
                    <div class="info__label">О проекте</div>
                    <div class="info__value">
                        SPA-приложение для мониторинга прокси<br>
                        Реализовано на Laravel 10 + Vue 3 + TypeScript<br>
                        Автоматическая и ручная проверка, WebSocket-обновления и настраиваемый интервал проверки<br>
                        Если добавлен телеграм бот, то уведомления о падении и поднятии прокси будут падать туда<br>
                        Адаптивная вёрстка
                    </div>
                </div>

                <div class="info__item">
                    <div class="info__label">Архитектура</div>
                    <div class="info__value">
                        Backend - REST API + Sanctum + Fortify<br>
                        Проверка прокси выполняется через Queue Jobs<br>
                        WebSocket-уведомления реализованы через Laravel Reverb<br>
                        Frontend - SPA на Vue 3
                    </div>
                </div>

                <div class="info__item">
                    <div class="info__label">Как работает проверка прокси</div>
                    <div class="info__value">
                        1. Прокси отправляется в очередь<br>
                        2. Выполняется запрос на api.ipify.org<br>
                        3. Результат сохраняется и отправляется через WebSocket
                    </div>
                </div>

                <div class="info__item">
                    <div class="info__label">Используемые компоненты и библиотеки</div>
                    <div class="info__value">
                        Laravel 10, sanctum, fortify, reverb<br>
                        Vue 3, TypeScript, vite, axios, vue-router, pusher-js, laravel-echo, lodash
                    </div>
                </div>

                <div class="info__item">
                    <div class="info__label">Сторонние API</div>
                    <div class="info__value">
                        1. api.ipify.org - сервис для получения внешнего IP<br>
                        2. ip-api.com - гео-IP сервис без ключа API (2ip.ru платный, остановился на этом варианте)<br>
                        требуют минимальной конфигурации и бесплатны
                    </div>
                </div>

                <div class="info__item">
                    <div class="info__label">Docker</div>
                    <div class="info__value">
                        Приложение запускается через docker-compose<br>
                        Контейнеры: nginx, php, mysql, artisan, composer, queue, reverb, node, scheduler
                    </div>
                </div>

            </div>

        </div>
    </section>
</template>