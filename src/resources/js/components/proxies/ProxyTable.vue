<template>
    <table class="table">
        <thead>
            <tr>
                <th>Прокси</th>
                <th>Тип</th>
                <th>Статус</th>
                <th>Последняя проверка</th>
                <th>Комментарий</th>
                <th>Действия</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="proxy in proxies.data" :key="proxy.id">

                <td>
                    <div class="copy__block">
                        <span>{{ proxy.raw }}</span>

                        <CopyButton :text="proxy.raw" @copied="emit('copied')" @error="emit('copyError')" />
                    </div>
                </td>

                <td>{{ proxy.type.toUpperCase() }}</td>

                <td>
                    <StatusBadge :status="proxy.status" />
                </td>

                <td>
                    {{ proxy.checked_at ? new Date(proxy.checked_at).toLocaleString('ru-RU', { hour12: false }) : '-' }}
                </td>

                <td>{{ proxy.comment ?? '-' }}</td>

                <td class="actions">
                    <button class="btn btn_theme_apply" @click="emit('check', proxy)">Проверить</button>
                    <button class="btn btn_theme_info" @click="emit('history', proxy)">История</button>
                    <button class="btn btn_theme_info" @click="emit('notifications', proxy)">Уведомления</button>
                    <button class="btn btn_theme_info" @click="emit('edit', proxy)">Редактировать</button>
                    <button class="btn btn_theme_danger" @click="emit('delete', proxy)">Удалить</button>
                </td>

            </tr>

            <tr v-if="!proxies.data?.length">
                <td colspan="6" class="empty">Нет данных</td>
            </tr>
        </tbody>
    </table>
</template>

<script setup>
import CopyButton from "../ui/CopyButton.vue"
import StatusBadge from "../ui/StatusBadge.vue"

defineProps({
    proxies: Object
})

const emit = defineEmits([
    "check", "history", "notifications",
    "edit", "delete", "copied", "copyError"
])
</script>