<template>
    <div class="filters">
        <div class="field">
            <label class="field__label">Поиск</label>
            <input class="input" placeholder="IP или комментарий" :value="search"
                @input="$emit('update:search', $event.target.value)" />
        </div>

        <div class="field">
            <label class="field__label">Тип</label>
            <select class="input" :value="type" @change="$emit('update:type', $event.target.value)">
                <option value="">Все</option>
                <option value="http">HTTP</option>
                <option value="socks5">SOCKS5</option>
            </select>
        </div>

        <div class="field">
            <label class="field__label">Статус</label>
            <select class="input" :value="status" @change="$emit('update:status', $event.target.value)">
                <option value="">Все</option>
                <option value="working">Работает</option>
                <option value="failed">Не работает</option>
                <option value="unknown">Не проверен</option>
            </select>
        </div>
    </div>
</template>

<script setup>
defineProps({
    search: String,
    type: String,
    status: String
})
</script>