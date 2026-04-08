<template>
<BaseModal :active="active" title="Переключить бота" @close="$emit('close')">

    <div class="modal__form">
        <p>Переключить бота {{ bot?.chat_id }} ?</p>

        <button class="btn btn_theme_info" @click="toggleBot">
            Переключить
        </button>
    </div>

</BaseModal>
</template>

<script setup>
import axios from 'axios'
import BaseModal from "@/components/ui/BaseModal.vue"
import { useAlert } from '@/composables/useAlert'

const props = defineProps({
    active:Boolean,
    bot:Object
})

const emit = defineEmits(['close','toggled'])
const { showAlert } = useAlert()

const toggleBot = async () => {

    try{
        await axios.post(route('bots.toggle', props.bot.id), { withCredentials:true })

        showAlert('Бот переключён','success')

        emit('close')
        emit('toggled')

    }catch(err){
        showAlert(err,'error')
    }

}
</script>