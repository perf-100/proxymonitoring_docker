<template>
    <div class="pagination">
        <button v-for="link in links" :key="link.label" class="pagination__btn" :class="{ active: link.active }" :disabled="!link.url" @click="link.url && emit('change', link.url)">
            <span v-html="link.label"></span>
        </button>
    </div>
</template>

<script setup>
defineProps({
    links: Array
})

const emit = defineEmits(["change"])
</script>