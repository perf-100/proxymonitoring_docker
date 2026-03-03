<template>
    <section class="auth">
        <div class="auth__card card container">
            <h2 class="auth__title">Вход в систему</h2>

            <form class="auth__form" @submit.prevent="submitLogin">

                <input v-model="email" class="input" type="email" placeholder="Email">
                <input v-model="password" class="input" type="password" placeholder="Пароль">

                <label class="checkbox">
                    <input type="checkbox" v-model="remember">
                    Запомнить меня
                </label>

                <button class="btn btn_theme_apply" type="submit">Войти</button>
            </form>

            <div class="auth__footer">
                Нет аккаунта?
                <router-link to="/registration">Зарегистрироваться</router-link>
            </div>

        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { user } from '../auth.js'
import { useAlert } from '@/composables/useAlert'

const email = ref('')
const password = ref('')
const remember = ref(false)

const router = useRouter()
const { showAlert } = useAlert()

async function submitLogin() {
    
    try {
        await axios.get('/sanctum/csrf-cookie', { withCredentials: true })

        const payload = {
            email: email.value,
            password: password.value,
            remember: remember.value
        }

        await axios.post('/login', payload, { withCredentials: true })
        const userRes = await axios.get('/api/user', { withCredentials: true })
        user.value = userRes.data

        router.push('/')

    } catch (e) {

        if (e.response?.data?.message) {
            showAlert(e.response.data.message, 'error')
        } else if (e.response?.status === 422 && e.response.data.errors) {
            const firstError = Object.values(e.response.data.errors)[0][0]
            showAlert(firstError, 'error')
        } else {
            showAlert('Сервер недоступен', 'error')
        }

    }
}
</script>