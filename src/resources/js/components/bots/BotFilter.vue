<template>
    <div class="filters">
        <div class="field">
            <label class="field__label">Поиск</label>
            <input class="input" placeholder="Чат или токен" :value="search" @input="$emit('update:search', $event.target.value)" />
        </div>

        <div class="field">
            <label class="field__label">Статус</label>
            <select class="input" :value="status" @change="$emit('update:status', $event.target.value)">
                <option value="">Все</option>
                <option value="active">Работает</option>
                <option value="disabled">Не работает</option>
            </select>
        </div>
    </div>
</template>

<script setup>
defineProps({
    search: String,
    status: String,
})

const emit = defineEmits(['update:search', 'update:status'])

</script>