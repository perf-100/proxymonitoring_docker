<template>
    <section class="proxies">
        <div class="proxies__wrapper container card">

            <div class="proxies__top">
                <h2>Список прокси</h2>

                <button class="btn btn_theme_apply" @click="openCreateModal()">
                    Добавить прокси
                </button>
            </div>

            <ProxyFilters v-model:search="filters.search" v-model:type="filters.type" v-model:status="filters.status" />

            <Loader v-if="loading" />
            <div class="proxies__table-wrapper" v-else>
                <ProxyTable :proxies="proxies" @check="checkNow" @history="openHistoryModal"
                    @notifications="openNotificationsModal" @edit="openEditModal($event)"
                    @delete="openDeleteModal($event)" />

                <BasePagination v-if="proxies.meta?.links" :links="proxies.meta?.links || []" @change="loadProxies" />
            </div>

        </div>

        <ProxyCreateModal
            :active="createModal"
            @close="createModal=false"
            @saved="handleSaved"
        />

        <ProxyEditModal
            :active="editModal"
            :proxy="selectedProxy"
            @close="editModal=false"
            @saved="handleSaved"
        />

        <ProxyDeleteModal
            :active="deleteModal"
            :proxy="selectedProxy"
            @close="deleteModal=false"
            @deleted="handleSaved"
        />

        <ProxyHistoryModal 
            :active="historyModalActive" 
            :proxy-id="historyProxyId" 
            @close="historyModalActive = false" 
        />

        <ProxyNotificationsModal 
            :active="notificationsModalActive" 
            :proxy-id="notificationsProxyId" 
            @close="notificationsModalActive = false" 
        />

    </section>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useProxies } from '@/composables/useProxies'
import { useAlert } from '@/composables/useAlert'

import ProxyTable from "@/components/proxies/ProxyTable.vue"
import BasePagination from "@/components/ui/BasePagination.vue"
import ProxyCreateModal from "@/components/proxies/ProxyCreateModal.vue"
import ProxyEditModal from "@/components/proxies/ProxyEditModal.vue"
import ProxyDeleteModal from "@/components/proxies/ProxyDeleteModal.vue"
import ProxyHistoryModal from "@/components/proxies/ProxyHistoryModal.vue"
import ProxyNotificationsModal from "@/components/proxies/ProxyNotificationsModal.vue"
import ProxyFilters from "@/components/proxies/ProxyFilters.vue"
import Loader from "@/components/ui/Loader.vue"
import { useProxyChannel } from '@/composables/useProxyChannel';


const { proxies, loading, filters, loadProxies, debouncedLoad, checkNow } = useProxies()
const { showAlert } = useAlert()

const proxyModalActive = ref(false)
const historyModalActive = ref(false)
const historyProxyId = ref(null)
const notificationsModalActive = ref(false)
const notificationsProxyId = ref(null)

const createModal = ref(false)
const editModal = ref(false)
const deleteModal = ref(false)

const selectedProxy = ref(null)

useProxyChannel(proxies);

onMounted(loadProxies);

watch([() => filters.search, () => filters.type, () => filters.status], () => debouncedLoad())


const openCreateModal = () => {
    createModal.value = true
}

const openEditModal = (proxy) => {
    selectedProxy.value = proxy
    editModal.value = true
}

const openDeleteModal = (proxy) => {
    selectedProxy.value = proxy
    deleteModal.value = true
}

const openHistoryModal = (proxy) => {
    historyProxyId.value = proxy.id
    historyModalActive.value = true
}

const openNotificationsModal = (proxy) => {
    notificationsProxyId.value = proxy.id
    notificationsModalActive.value = true
}

const handleSaved = () => {
    proxyModalActive.value = false
    showAlert('Прокси сохранён', 'success')
    loadProxies()
}

</script>