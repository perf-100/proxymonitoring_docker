<template>
    <BaseModal :active="active" title="Добавить бота" @close="$emit('close')">

        <form class="modal__form" @submit.prevent="saveBot">
            <div class="field">
                <label class="field__label">Токен</label>
                <input class="input" v-model="botToken" placeholder="XXXX:XXXXXXX">
            </div>

            <div class="field">
                <label class="field__label">ID чата</label>
                <input class="input" v-model="chatId" placeholder="-123456">
            </div>

            <button class="btn btn_theme_apply">Сохранить</button>
        </form>

    </BaseModal>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import BaseModal from "@/components/ui/BaseModal.vue"
import { useAlert } from '@/composables/useAlert'

const props = defineProps({ 
    active: Boolean 
})

const emit = defineEmits(['close','saved'])

const { showAlert } = useAlert()

const botToken = ref('')
const chatId = ref('')

const resetForm = () => {
    botToken.value = ''
    chatId.value = ''
}

watch(() => props.active, (isActive) => {
    if (isActive) resetForm()
})

const saveBot = async () => {
    try {

        const payload = {
            bot_token: botToken.value,
            chat_id: chatId.value
        }
        
        await axios.post(route('bots.store'), payload, { withCredentials: true })

        showAlert('Бот добавлен','success')
        resetForm()

        emit('close')
        emit('saved')

    } catch(err){

        if(err.response?.status === 422){
            const msg = Object.values(err.response.data.errors)[0][0]
            showAlert(msg,'error')
        } else {
            showAlert(err,'error')
        }

    }
}
</script>