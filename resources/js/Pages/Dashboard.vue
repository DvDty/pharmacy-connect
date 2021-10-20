<script setup lang="ts">
import Layout from '@/Layouts/App.vue'
import {Head} from '@inertiajs/inertia-vue3'
import {PropType} from 'vue';
import {RefreshIcon, SearchIcon} from "@heroicons/vue/solid";

const props = defineProps({
    distributors: Array as PropType<Array<App.Models.Distributor>>,
})

function displayStatus(updating: boolean): string {
    return updating ? 'Обновяване' : 'В готовност'
}
</script>

<template>
    <Head><title>Dashboard</title></Head>

    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Medication Search</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200">Дистрибутори</div>

                    <div class="flex">
                        <div v-for="distributor in distributors"
                             class="max-w-sm rounded overflow-hidden shadow-l"
                        >
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2">
                                    <RefreshIcon class="h-6 w-6 text-blue-500 inline"/>
                                    {{ distributor.name }}
                                </div>

                                <p class="text-gray-700 text-base">
                                    {{ distributor.total_products }} / {{ distributor.total_products }}
                                </p>

                                <p class="text-gray-700 text-base">
                                    Статус:
                                    <span
                                        class="inline-block rounded-full px-2 py-1 text-sm font-semibold text-gray-700"
                                        :class="[ distributor.updating ? 'bg-yellow-500 animate-pulse' : 'bg-green-400' ]"
                                    >
                                        {{ displayStatus(distributor.updating) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200 flex">
                        <div class="flex items-center">
                            <SearchIcon class="w-6 h-6 text-blue-500 "/>
                        </div>
                        <input
                            type="text"
                            placeholder="Търси..."
                            name="search"
                            class="leading-tight border-0 focus:ring-0 w-full"
                            autocomplete="off"
                        >
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
