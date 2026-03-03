<template>
    <BaseModal :active="active" :title="modalTitle" @close="$emit('close')">

        <form v-if="mode === 'add'" class="modal__form" @submit.prevent="saveBot">
            <div class="field">
                <label class="field__label">Токен</label>
                <input class="input" v-model="botToken" placeholder="XXXX:XXXXXXX">
            </div>

            <div class="field">
                <label class="field__label">ID чата</label>
                <input class="input" v-model="chatId" placeholder="-123456">
            </div>

            <button class="btn btn_theme_apply" type="submit">Сохранить</button>
        </form>


        <div v-if="mode === 'delete'" class="modal__form">
            <p>Вы действительно хотите удалить {{ data?.chat_id }}?</p>
            <button class="btn btn_theme_danger" @click="deleteBot">Удалить</button>
        </div>


        <div v-if="mode === 'toggle'" class="modal__form">
            <p>Вы действительно хотите переключить {{ data?.chat_id }}?</p>
            <button class="btn btn_theme_info" @click="toggleBot">Переключить</button>
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
    mode: String, // add, delete, toggle
    data: Object
})
const emit = defineEmits(['close', 'saved'])

const { showAlert } = useAlert()

const botToken = ref('')
const chatId = ref('')

watch(() => props.data, (newData) => {
    if (newData) {
        botToken.value = newData.bot_token || ''
        chatId.value = newData.chat_id || ''
    } else {
        botToken.value = ''
        chatId.value = ''
    }
}, { immediate: true })

const modalTitle = computed(() =>
    props.mode === 'add' ? 'Добавить бота' :
    props.mode === 'toggle' ? 'Переключить бота' :
    props.mode === 'delete' ? 'Удалить бота?' : ''
)

const saveBot = async () => {
    try {
        const payload = {
            bot_token: botToken.value,
            chat_id: chatId.value,
        }

        await axios.post('/api/bots', payload, { withCredentials: true })

        showAlert('Бот успешно сохранён', 'success')
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

const toggleBot = async () => {
    try {
        await axios.get(`/api/bots/${props.data.id}/toggle`, { withCredentials: true })
        showAlert('Бот переключён', 'success')
        emit('saved')
        emit('close')
    } catch (e) {
        showAlert(e, 'error')
    }
}

const deleteBot = async () => {
    try {
        await axios.delete(`/api/bots/${props.data.id}`, { withCredentials: true })
        showAlert('Бот удалён', 'success')
        emit('saved')
        emit('close')
    } catch (e) {
        showAlert(e, 'error')
    }
}

</script>