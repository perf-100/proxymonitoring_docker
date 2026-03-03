<template>
    <div class="modal" :class="{ active: active }" @click.self="$emit('close')">
        <div class="modal__content card">
            <div class="modal__header">
                <h2>{{ title }}</h2>
                <div class="modal__close" @click="$emit('close')">✕</div>
            </div>
            <div class="modal__body">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    active: Boolean,
    title: String
})
defineEmits(['close'])
</script>