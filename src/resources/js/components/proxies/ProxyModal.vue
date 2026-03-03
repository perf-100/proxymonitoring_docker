<template>
    <BaseModal :active="active" :title="modalTitle" @close="$emit('close')">

        <form v-if="mode === 'add' || mode === 'edit'" class="modal__form" @submit.prevent="saveProxy">
            <div class="field">
                <label class="field__label">
                    IP:PORT
                    <span class="hint">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                            <path d="M12 16V12" stroke="currentColor" stroke-width="2" />
                            <circle cx="12" cy="8" r="1" fill="currentColor" />
                        </svg>
                        <span class="hint__tooltip">
                            Формат:<br>
                            IP:PORT<br>
                            IP:PORT:LOGIN:PASSWORD<br>
                            ADRESS:PORT<br>
                            ADRESS:PORT:LOGIN:PASSWORD<br>
                            TYPE://ADRESS:PORT:LOGIN:PASSWORD
                        </span>
                    </span>
                </label>
                <input class="input" v-model="proxyString" placeholder="Пример: 128.70.189.61:8080">
            </div>

            <div class="field">
                <label class="field__label">Интервал проверки (секунды)</label>
                <select class="input" v-model.number="checkInterval">
                    <option :value="60">60</option>
                    <option :value="300">300</option>
                    <option :value="600">600</option>
                </select>
            </div>

            <div class="field">
                <label class="field__label">Комментарий</label>
                <textarea class="input" v-model="comment" placeholder="Например: прокси для тестов"></textarea>
            </div>

            <button class="btn btn_theme_apply" type="submit">Сохранить</button>
        </form>


        <div v-if="mode === 'delete'" class="modal__form">
            <p>Вы действительно хотите удалить {{ data?.raw }}?</p>
            <button class="btn btn_theme_danger" @click="deleteProxy">Удалить</button>
        </div>

    </BaseModal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import BaseModal from "@/components/ui/BaseModal.vue"
import { useAlert } from '@/composables/useAlert'

const props = defineProps({
    active: Boolean,
    mode: String, // add, edit, delete
    data: Object
})
const emit = defineEmits(['close', 'saved'])

const { showAlert } = useAlert()

const proxyString = ref('')
const comment = ref('')
const checkInterval = ref(60)

watch(() => props.data, (newData) => {
    proxyString.value = newData?.raw || ''
    comment.value = newData?.comment || ''
    checkInterval.value = newData?.check_interval || 60
}, { immediate: true })

const modalTitle = computed(() =>
    props.mode === 'add' ? 'Добавить прокси' :
    props.mode === 'edit' ? 'Редактировать прокси' :
    props.mode === 'delete' ? 'Удалить прокси?' : ''
)

const saveProxy = async () => {
    try {
        const payload = {
            proxy_string: proxyString.value,
            comment: comment.value,
            check_interval: checkInterval.value
        }

        if (props.mode === 'edit') {
            await axios.put(`/api/proxies/${props.data.id}`, payload, { withCredentials: true })
        } else if (props.mode === 'add') {
            await axios.post('/api/proxies', payload, { withCredentials: true })
        }

        showAlert('Прокси успешно сохранён', 'success')
        emit('saved')
        emit('close')

    } catch (e) {

        if (e.response?.status === 422) {
            const firstError = Object.values(e.response.data.errors)[0][0]
            showAlert(firstError, 'error')
        } else {
            showAlert(e, 'error')
        }

    }
}

const deleteProxy = async () => {
    try {
        await axios.delete(`/api/proxies/${props.data.id}`, { withCredentials: true })
        showAlert('Прокси удалён', 'success')
        emit('saved')
        emit('close')
    } catch (e) {
        showAlert(e, 'error')
    }
}

</script>