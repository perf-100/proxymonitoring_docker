<template>
    <BaseModal :active="active" title="Удалить прокси" @close="$emit('close')">

        <div class="modal__form">

            <p>Удалить {{ proxy?.raw }}?</p>

            <button class="btn btn_theme_danger" @click="deleteProxy">
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
    active: Boolean,
    proxy: Object
})

const emit = defineEmits(['close', 'deleted'])

const { showAlert } = useAlert()

const deleteProxy = async () => {

    try {

        await axios.delete(route('proxies.destroy', props.proxy.id), { withCredentials: true })

        showAlert('Прокси удалён', 'success')

        emit('close')
        emit('deleted')

    } catch (err) {
        showAlert(err, 'error')
    }

}
</script>