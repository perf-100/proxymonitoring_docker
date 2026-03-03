import { ref } from 'vue'
import axios from 'axios'

export const user = ref(null)

export async function fetchUser() {
    try {
        const res = await axios.get('/api/user', { withCredentials: true })
        user.value = res.data
    } catch (err) {
        user.value = null
    }
}

export async function logout() {
    try {
        await axios.post('/logout', {}, { withCredentials: true })
    } catch (err) {
        console.warn('Ошибка логаута', err)
    }

    user.value = null

    // чтобы следующий логин прошёл
    try {
        await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
    } catch (err) {
        console.warn('Не удалось обновить CSRF cookie', err)
    }
}