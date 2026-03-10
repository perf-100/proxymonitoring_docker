<template>
    <BaseModal :active="active" title="Добавить прокси" @close="$emit('close')">

        <form class="modal__form" @submit.prevent="saveProxy">

            <div class="field">
                <label class="field__label">IP:PORT</label>
                <input class="input" v-model="proxyString" placeholder="192.168.0.1:80">
            </div>

            <div class="field">
                <label class="field__label">Интервал проверки</label>
                <select class="input" v-model.number="checkInterval">
                    <option :value="60">60</option>
                    <option :value="300">300</option>
                    <option :value="600">600</option>
                </select>
            </div>

            <div class="field">
                <label class="field__label">Комментарий</label>
                <textarea class="input" v-model="comment" placeholder="прокси для тестов" />
            </div>

            <button class="btn btn_theme_apply">Сохранить</button>

        </form>

    </BaseModal>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import BaseModal from "@/components/ui/BaseModal.vue"
import { useAlert } from '@/composables/useAlert'

defineProps({ 
    active: Boolean 
})

const emit = defineEmits(['close', 'saved'])

const { showAlert } = useAlert()

const proxyString = ref('')
const comment = ref('')
const checkInterval = ref(60)

const resetForm = () => {
    proxyString.value = ''
    comment.value = ''
    checkInterval.value = 60
}

const saveProxy = async () => {
    try {
        const payload = {
            proxy_string: proxyString.value,
            comment: comment.value,
            check_interval: checkInterval.value
        }

        await axios.post(route('bots.store'), payload, { withCredentials: true })

        showAlert('Прокси добавлен', 'success')
        resetForm()

        emit('close')
        emit('saved')

    } catch (err) {

        if (err.response?.status === 422) {
            const msg = Object.values(err.response.data.errors)[0][0]
            showAlert(msg, 'error')
        } else {
            showAlert(err, 'error')
        }

    }

}
</script>