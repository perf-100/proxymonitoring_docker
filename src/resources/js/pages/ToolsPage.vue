<template>
    <section class="info">
        <div class="info__wrapper container card">

            <h2>Информация о вашем IP</h2>

            <div v-if="loading" class="loading-overlay"><div class="spinner"></div></div>

            <div v-else class="info__grid">

                <div class="info__item">
                    <div class="info__label">IP</div>
                    <div class="info__value">{{ info.query }}</div>
                </div>

                <div class="info__item">
                    <div class="info__label">Country</div>
                    <div class="info__value">{{ info.country }}</div>
                </div>

                <div class="info__item">
                    <div class="info__label">Прокси</div>
                    <div class="info__value">{{ info.proxy ? 'Да' : 'Нет' }}</div>
                </div>

            </div>

        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
const info = ref({})
const loading = ref(true)

const loadInfo = async () => {
    loading.value = true
    try {
        const res = await axios.get(route('tools.index'), { withCredentials: true })
        info.value = res.data
    } catch (e) {
        showAlert('Не удалось загрузить информацию', 'error')
        console.error(e)
    } finally {
        loading.value = false
    }
}

onMounted(loadInfo)
</script>