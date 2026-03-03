<template>
    <table class="table">
        <thead>
            <tr>
                <th>Чат</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="bot in bots.data" :key="bot.id">

                <td>{{ bot.chat_id }}</td>

                <td>
                    <StatusBadge :status="bot.status" />
                </td>

                <td class="actions">
                    <button class="btn btn_theme_info" @click="emit('toggle', bot)">Переключить</button>
                    <button class="btn btn_theme_danger" @click="emit('delete', bot)">Удалить</button>
                </td>

            </tr>

            <tr v-if="!bots.data?.length">
                <td colspan="6" class="empty">Нет данных</td>
            </tr>
        </tbody>
    </table>
</template>

<script setup>
import StatusBadge from "../ui/StatusBadge.vue"

defineProps({
    bots: Object
})

const emit = defineEmits([
    "delete", "toggle"
])
</script>