<template>
    <section class="proxies">
        <div class="proxies__wrapper container card">

            <div class="proxies__top">
                <h2>Список прокси</h2>

                <button class="btn btn_theme_apply" @click="openProxyModal('add')">
                    Добавить прокси
                </button>
            </div>

            <ProxyFilters v-model:search="filters.search" v-model:type="filters.type" v-model:status="filters.status" />

            <Loader v-if="loading" />
            <div class="proxies__table-wrapper" v-else>
                <ProxyTable :proxies="proxies" @check="checkNow" @history="openHistoryModal"
                    @notifications="openNotificationsModal" @edit="openProxyModal('edit', $event)"
                    @delete="openProxyModal('delete', $event)" />

                <BasePagination v-if="proxies.links" :links="proxies.links" @change="loadProxies" />
            </div>

        </div>

        <ProxyModal :active="proxyModalActive" :mode="proxyModalMode" :data="proxyModalData" @close="proxyModalActive = false" @saved="handleSaved" />

        <ProxyHistoryModal :active="historyModalActive" :data="historyModalData" @close="historyModalActive = false" />

        <ProxyNotificationsModal :active="notificationsModalActive" :data="notificationsModalData" @close="notificationsModalActive = false" />

    </section>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useProxies } from '@/composables/useProxies'
import { useAlert } from '@/composables/useAlert'

import ProxyTable from "@/components/proxies/ProxyTable.vue"
import BasePagination from "@/components/ui/BasePagination.vue"
import ProxyModal from "@/components/proxies/ProxyModal.vue"
import ProxyHistoryModal from "@/components/proxies/ProxyHistoryModal.vue"
import ProxyNotificationsModal from "@/components/proxies/ProxyNotificationsModal.vue"
import ProxyFilters from "@/components/proxies/ProxyFilters.vue"
import Loader from "@/components/ui/Loader.vue"
import { useProxyChannel } from '@/composables/useProxyChannel';


const { proxies, loading, filters, loadProxies, debouncedLoad, checkNow } = useProxies()
const { showAlert } = useAlert()

const proxyModalActive = ref(false)
const proxyModalMode = ref('add')
const proxyModalData = ref(null)
const historyModalActive = ref(false)
const historyModalData = ref(null)
const notificationsModalActive = ref(false)
const notificationsModalData = ref(null)

useProxyChannel(proxies);

onMounted(loadProxies);

watch([() => filters.search, () => filters.type, () => filters.status], debouncedLoad)



const openProxyModal = (mode, data = null) => {
    proxyModalMode.value = mode
    proxyModalData.value = data
    proxyModalActive.value = true
}

const openHistoryModal = async (proxy) => {
    try {
        const res = await axios.get(`/api/proxies/checks/${proxy.id}`)
        historyModalData.value = res.data
        historyModalActive.value = true
    } catch (e) {
        showAlert(e, 'error')
    }
}

const openNotificationsModal = async (proxy) => {
    try {
        const res = await axios.get(`/api/notifications/${proxy.id}`)
        notificationsModalData.value = res.data
        notificationsModalActive.value = true
    } catch (e) {
        showAlert(e, 'error')
    }
}

const handleSaved = () => {
    proxyModalActive.value = false
    showAlert('Прокси сохранён', 'success')
    loadProxies()
}

</script>