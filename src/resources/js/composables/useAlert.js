import { ref } from 'vue'

const message = ref('')
const type = ref('success')

export function useAlert() {

    const showAlert = (text, alertType = 'success') => {
        message.value = text
        type.value = alertType

        setTimeout(() => {
            message.value = ''
        }, 3000)
    }

    return {
        message,
        type,
        showAlert
    }
}