<template>
    <BaseModal :active="active" title="История проверок" @close="$emit('close')">
        <table class="table">
            <thead>
                <tr>
                    <th>Время</th>
                    <th>Статус</th>
                    <th>IP</th>
                    <th>Ответ</th>
                    <th>Ошибка</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in modalData.data || []" :key="item.id">
                    <td>{{ formatDate(item.created_at) }}</td>
                    <td>
                        <StatusBadge :status="item.status" />
                    </td>
                    <td>{{ item.ip_addr ?? '-' }}</td>
                    <td>{{ item.time ? item.time + ' ms' : '-' }}</td>
                    <td class="break-words">{{ item.message ?? '-' }}</td>
                </tr>
                <tr v-if="!(modalData.data?.length)">
                    <td colspan="5" class="empty">Нет данных</td>
                </tr>
            </tbody>
        </table>

        <BasePagination :links="modalData.links" @change="loadModalPage" />
        
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
    data: Object
})

const modalData = ref({ data: [], links: [] })

watch(() => props.data, (newData) => {
    if (newData) modalData.value = { ...newData }
}, { immediate: true })

const formatDate = dt => dt ? new Date(dt).toLocaleString("ru-RU", { hour12: false }) : "-"

const loadModalPage = async (url) => {
    if (!url) return
    try {
        const res = await axios.get(url, { withCredentials: true })
        modalData.value = res.data
    } catch (err) {
        console.error(err)
    }
}

</script>