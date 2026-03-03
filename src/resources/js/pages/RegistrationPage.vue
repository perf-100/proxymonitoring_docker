<template>
    <section class="auth">
        <div class="auth__card card container">

            <h2 class="auth__title">Регистрация</h2>

            <form class="auth__form" @submit.prevent="submitRegistration">

                <input v-model="name" class="input" type="text" placeholder="Имя" required>
                <input v-model="email" class="input" type="email" placeholder="Email" required>
                <input v-model="password" class="input" type="password" placeholder="Пароль" required>
                <input v-model="password_confirmation" class="input" type="password" placeholder="Подтвердите пароль"
                    required>

                <button class="btn btn_theme_apply" type="submit" :disabled="loading">
                    {{ loading ? 'Регистрация...' : 'Создать аккаунт' }}
                </button>

            </form>

            <div class="auth__footer">
                Уже есть аккаунт?
                <router-link to="/login">Войти</router-link>
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

const router = useRouter()
const { showAlert } = useAlert()

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const loading = ref(false)

async function submitRegistration() {
    loading.value = true

    try {
        await axios.get('/sanctum/csrf-cookie', { withCredentials: true })

        const payload = {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        }

        await axios.post('/register', payload, { withCredentials: true })
        const userRes = await axios.get('/api/user', { withCredentials: true })
        user.value = userRes.data
        router.push('/')

    } catch (e) {
        if (e.response?.status === 422) {
            const firstError = Object.values(e.response.data.errors)[0][0]
            showAlert(firstError, 'error')
        } else if (e.response?.data?.message) {
            showAlert(e.response.data.message, 'error')
        } else {
            showAlert('Сервер недоступен', 'error')
        }
    } finally {
        loading.value = false
    }
}
</script>