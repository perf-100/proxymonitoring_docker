<template>
    <header class="header">
        <div class="header__wrapper container">
            <div class="header__inner">
                <div class="header__logo">Прокси<span>Мониторинг</span></div>

                <div class="header__list-wrapper" v-if="user && user.id">
                    <ul class="header__list">
                        <li class="header__item"><router-link to="/">Главная</router-link></li>
                        <li class="header__item"><router-link to="/proxies">Прокси</router-link></li>
                        <li class="header__item"><router-link to="/bots">Боты</router-link></li>
                        <li class="header__item"><router-link to="/tools">Инструменты</router-link></li>
                    </ul>
                </div>
            </div>

            <button v-if="user && user.id" class="btn btn_theme_apply" @click="handleLogout">
                Выход
            </button>
            <div v-else>
                <router-link class="btn btn_theme_apply" to="/login">Войти</router-link>
            </div>
        </div>

    </header>
</template>


<script setup>
import { user, logout } from '../auth.js'
import { useRouter } from 'vue-router'

const router = useRouter()

async function handleLogout() {
    await logout()
    router.push('/login')
}

</script>