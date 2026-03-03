import { ref, reactive } from 'vue'
import axios from 'axios'
import { useAlert } from '@/composables/useAlert'
import debounce from 'lodash/debounce'

export function useProxies() {
    const proxies = ref({})
    const loading = ref(false)
    const { showAlert } = useAlert()

    const filters = reactive({
        search: '',
        type: '',
        status: ''
    })

    const loadProxies = async () => {
        loading.value = true
        try {
            const { data } = await axios.get('/api/proxies', {
                params: filters,
                withCredentials: true
            })
            proxies.value = data
        } catch (e) {
            showAlert(e, 'error')
        } finally {
            loading.value = false
        }
    }

    const debouncedLoad = debounce(loadProxies, 400)

    const checkNow = async (proxy) => {
        try {
            await axios.post(`/api/proxies/${proxy.id}/check`, {}, { withCredentials: true })
            showAlert('Проверка запущена', 'success')
            await loadProxies()
        } catch (e) {
            showAlert(e, 'error')
        }
    }

    return {
        proxies,
        loading,
        filters,
        loadProxies,
        debouncedLoad,
        checkNow
    }
}