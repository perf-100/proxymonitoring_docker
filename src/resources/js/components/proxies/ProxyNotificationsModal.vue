<template>
    <BaseModal :active="active" title="Уведомления" @close="$emit('close')">
        <table class="table">
            <thead>
                <tr>
                    <th>Время</th>
                    <th>Статус</th>
                    <th>Сообщение</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in modalData.data || []" :key="item.id">
                    <td>{{ formatDate(item.created_at) }}</td>
                    <td>
                        <StatusBadge :status="item.status" />
                    </td>
                    <td v-html="item.message" class="break-words"></td>
                </tr>
                <tr v-if="!(modalData.data?.length)">
                    <td colspan="3" class="empty">Нет данных</td>
                </tr>
            </tbody>
        </table>

        <BasePagination :links="modalData.meta?.links || []" @change="loadModalPage" />

    </BaseModal>
</template>

<script setup>
import BaseModal from "@/components/ui/BaseModal.vue"
import BasePagination from "@/components/ui/BasePagination.vue"
import StatusBadge from "../ui/StatusBadge.vue"
import { ref, watch } from "vue"
import axios from "axios"

const props = defineProps({
    active: Boolean,
    proxyId: Number
})

const emit = defineEmits(['close'])

const modalData = ref({ data: [], links: [] })

watch([() => props.active, () => props.proxyId],
    ([isActive, proxyId]) => {
        if (isActive && proxyId) {
            resetModal()
            loadModalPage()
        }
    }
)

const formatDate = dt => dt ? new Date(dt).toLocaleString("ru-RU", { hour12: false }) : "-"

const loadModalPage = async (url = null) => {
    if (!props.proxyId) return

    const requestUrl = url || route('notifications.index', props.proxyId)
    try {
        const res = await axios.get(requestUrl, { withCredentials: true })
        modalData.value = res.data
    } catch (err) {
        console.error(err)
    }
}

const resetModal = () => {
    modalData.value = { data: [], meta: { links: [] } }
}

</script>