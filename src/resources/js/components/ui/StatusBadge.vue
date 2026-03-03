<template>
    <span class="badge__status" :class="className">
        {{ label }}
    </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    status: String
})

const className = computed(() => ({
    ok: props.status === 'working' || props.status === 'send' || props.status === 'active',
    fail: props.status === 'failed' || props.status === 'notsend' || props.status === 'disabled',
    unknown: props.status === 'unknown'
}))

const label = computed(() => 
    props.status === 'working' ? 'Работает' :
    props.status === 'send' ? 'Отправлено' :
    props.status === 'notsend' ? 'Не отправлено' :
    props.status === 'failed' ? 'Не работает' :
    props.status === 'active' ? 'Работает' :
    props.status === 'disabled' ? 'Не работает' :
    'Не проверен'
)
</script>