<template>
    <section class="bots">
        <div class="bots__wrapper container card">

            <div class="bots__top">
                <h2>Список ботов</h2>

                <button class="btn btn_theme_apply" @click="openBotModal('add')">
                    Добавить бота
                </button>
            </div>

            <BotFilter v-model:search="filters.search" v-model:status="filters.status"/>

            <Loader v-if="loading" />
            <div class="bots__table-wrapper" v-else>
                <BotTable :bots="bots" @toggle="openBotModal('toggle', $event)"
                    @delete="openBotModal('delete', $event)" />

                <BasePagination v-if="bots.links" :links="bots.links" @change="loadBots" />
            </div>

        </div>

        <BotModal :active="botModalActive" :mode="botModalMode" :data="botModalData" @close="botModalActive = false" @saved="handleSaved" />

    </section>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useBots } from '@/composables/useBots'
import { useAlert } from '@/composables/useAlert'

import BasePagination from "@/components/ui/BasePagination.vue"
import Loader from "@/components/ui/Loader.vue"
import BotFilter from '../components/bots/BotFilter.vue'
import BotTable from '../components/bots/BotTable.vue'
import BotModal from '../components/bots/BotModal.vue'

const { bots, loading, filters, loadBots, debouncedLoad } = useBots()
const { showAlert } = useAlert()

const botModalActive = ref(false)
const botModalMode = ref('add')
const botModalData = ref(null)

onMounted(loadBots)
watch([() => filters.search, () => filters.status], debouncedLoad)



const openBotModal = (mode, data = null) => {
    botModalMode.value = mode
    botModalData.value = data
    botModalActive.value = true
}

const handleSaved = () => {
    botModalActive.value = false
    showAlert('Бот сохранён', 'success')
    loadBots()
}

</script>