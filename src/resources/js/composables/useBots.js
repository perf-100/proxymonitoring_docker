import { ref, reactive } from 'vue'
import axios from 'axios'
import { useAlert } from '@/composables/useAlert'
import debounce from 'lodash/debounce'

export function useBots() {
    const bots = ref({})
    const loading = ref(false)
    const { showAlert } = useAlert()

    const filters = reactive({
        search: '',
        status: '',
    })

    const loadBots = async () => {
        loading.value = true
        try {
            const { data } = await axios.get('/api/bots', {
                params: filters,
                withCredentials: true
            })
            bots.value = data
        } catch (e) {
            showAlert(e, 'error')
        } finally {
            loading.value = false
        }
    }

    const debouncedLoad = debounce(loadBots, 400)

    return {
        bots,
        loading,
        filters,
        loadBots,
        debouncedLoad,
    }
}