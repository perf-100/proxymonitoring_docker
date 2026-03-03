<template>
    <div v-if="message" :class="['alert', type === 'success' ? 'success' : 'error']">
        {{ message }}
    </div>
</template>

<script setup>
import { useAlert } from '@/composables/useAlert'

const { message, type } = useAlert()
</script>