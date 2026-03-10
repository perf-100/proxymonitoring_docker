<template>
    <section class="bots">
        <div class="bots__wrapper container card">

            <div class="bots__top">
                <h2>Список ботов</h2>

                <button class="btn btn_theme_apply" @click="openCreateModal">
                    Добавить бота
                </button>
            </div>

            <BotFilter v-model:search="filters.search" v-model:status="filters.status"/>

            <Loader v-if="loading" />
            <div class="bots__table-wrapper" v-else>
                <BotTable :bots="bots" @toggle="openToggleModal($event)"
                    @delete="openDeleteModal($event)" />

                <BasePagination v-if="bots.meta?.links" :links="bots.meta?.links || []" @change="loadBots" />
            </div>

        </div>

        <BotCreateModal
            :active="createModal"
            @close="createModal=false"
            @saved="handleSaved"
        />

        <BotDeleteModal
            :active="deleteModal"
            :bot="selectedBot"
            @close="deleteModal=false"
            @deleted="handleSaved"
        />

        <BotToggleModal
            :active="toggleModal"
            :bot="selectedBot"
            @close="toggleModal=false"
            @toggled="handleSaved"
        />

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

import BotCreateModal from '../components/bots/BotCreateModal.vue'
import BotDeleteModal from '../components/bots/BotDeleteModal.vue'
import BotToggleModal from '../components/bots/BotToggleModal.vue'

const { bots, loading, filters, loadBots, debouncedLoad } = useBots()
const { showAlert } = useAlert()

const createModal = ref(false)
const deleteModal = ref(false)
const toggleModal = ref(false)

const selectedBot = ref(null)

onMounted(loadBots)
watch([() => filters.search, () => filters.status], () => debouncedLoad())

const openCreateModal = () => {
    createModal.value = true
}

const openDeleteModal = (bot) => {
    selectedBot.value = bot
    deleteModal.value = true
}

const openToggleModal = (bot) => {
    selectedBot.value = bot
    toggleModal.value = true
}

const handleSaved = () => {
    showAlert('Бот сохранён','success')
    loadBots()
}
</script>