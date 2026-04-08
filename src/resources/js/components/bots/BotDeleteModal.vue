<template>
<BaseModal :active="active" title="Удалить бота" @close="$emit('close')">

    <div class="modal__form">
        <p>Удалить бота {{ bot?.chat_id }} ?</p>

        <button class="btn btn_theme_danger" @click="deleteBot">
            Удалить
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

const emit = defineEmits(['close','deleted'])
const { showAlert } = useAlert()

const deleteBot = async () => {

    try{

        await axios.delete(route('bots.destroy', props.bot.id),{withCredentials:true})

        showAlert('Бот удалён','success')

        emit('close')
        emit('deleted')

    }catch(err){
        showAlert(err,'error')
    }
}

</script>