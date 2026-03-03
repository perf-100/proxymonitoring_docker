<template>
    <button class="icon-btn" @click="copy" title="Скопировать">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2" />
            <rect x="2" y="2" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2" />
        </svg>
    </button>
</template>

<script setup>
const props = defineProps({
    text: String
})

const emit = defineEmits(["copied", "error"])

const copy = async () => {
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(props.text)
        } else {
            const textarea = document.createElement("textarea")
            textarea.value = props.text
            document.body.appendChild(textarea)
            textarea.select()
            document.execCommand("copy")
            textarea.remove()
        }
        emit("copied")
    } catch {
        emit("error")
    }
}
</script>