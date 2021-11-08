<script setup lang="ts">
import {PropType, reactive, ref, watch} from 'vue'
import Layout from '@/Layouts/App.vue'
import {Head} from '@inertiajs/inertia-vue3'
import {RefreshIcon, SearchIcon} from '@heroicons/vue/solid'
import {humanizeDate} from '@/helpers'
import {Inertia} from '@inertiajs/inertia'
import axios from 'axios'
import {throttle} from 'lodash'
import useRoute from '@/Hooks/useRoute'

const props = defineProps({
    distributors: Array as PropType<Array<App.Models.Distributor>>,
    products: Array as PropType<Array<App.Models.PhoenixPharmaProduct>>,
})

const route = useRoute()

const spinRefreshIconIds = reactive([]);

let search = ref('')

watch(search, throttle(search => reloadProducts(search), 1500, {leading: false}))

setInterval(function () {
    if (props.distributors.some(distributor => distributor.updating)) {
        reloadDistributors()
    }
}, 5000)

function displayStatus(updating: boolean): string {
    return updating ? 'Обновяване' : 'В готовност'
}

function updateDistributorProducts(distributorId: number): void {
    spinDistributorIcon(distributorId)

    axios.post('/api/update-products', {distributorId})
    reloadDistributors()

    setTimeout(() => stopSpinningDistributorIcon(distributorId), 2000)
}

function reloadProducts(search: string): void {
    Inertia.visit(route('dashboard'), {
        only: ['products'],
        data: {search},
        preserveState: true,
    })
}

function reloadDistributors(): void {
    Inertia.reload({only: ['distributors']})
}

function spinDistributorIcon(distributorId: number): void {
    spinRefreshIconIds.push(distributorId)
}

function stopSpinningDistributorIcon(distributorId: number): void {
    spinRefreshIconIds.splice(spinRefreshIconIds.indexOf(distributorId), 1)
}
</script>

<template>
    <Head><title>Dashboard</title></Head>

    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Medication Search</h2>
        </template>

        <div class="pt-5 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg shadow">
                <div class="p-4 bg-white border-b border-gray-200">Дистрибутори</div>

                <div class="flex">
                    <div v-for="distributor in distributors"
                         class="max-w-sm rounded overflow-hidden shadow-l"
                    >
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">
                                <RefreshIcon
                                    class="h-6 w-6 text-blue-500 inline cursor-pointer"
                                    :class="[ spinRefreshIconIds.includes(distributor.id) ? 'animate-spin' : '']"
                                    v-on:click="updateDistributorProducts(distributor.id)"
                                />
                                {{ distributor.name }}
                            </div>

                            <p class="text-gray-700 text-base">
                                Статус:
                                <span
                                    class="inline-block rounded-full px-2 py-1 text-sm font-semibold text-gray-700"
                                    :class="[ distributor.updating ? 'bg-yellow-500 animate-pulse' : 'bg-green-400' ]"
                                >
                                    {{ displayStatus(distributor.updating) }}
                                </span>
                            </p>

                            <p class="text-gray-700 text-base">
                                <span v-if="distributor.updating">
                                    {{ distributor.updated_products }} / {{ distributor.total_products }}
                                </span>

                                <span v-else>
                                    {{ distributor.total_products }} продукта
                                </span>
                            </p>

                            <div v-if="!distributor.updating">
                                <p class="text-gray-700 text-base">Последно обновяване:</p>
                                <p class="text-gray-700 text-base">{{ humanizeDate(distributor.last_updated) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-4 bg-white border-b border-gray-200 flex">
                    <div class="flex items-center">
                        <SearchIcon class="w-6 h-6 text-blue-500 "/>
                    </div>
                    <input
                        type="text"
                        placeholder="Търси..."
                        v-model="search"
                        class="leading-tight border-0 focus:ring-0 w-full"
                        autocomplete="off"
                    >
                </div>

                <div v-if="products" class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Име
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Дистрибутор
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Производител
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Продажна цена
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                НЗОК цена
                                            </th>

                                            <!--button-->
                                            <!--<th scope="col" class="relative px-6 py-3">-->
                                            <!--    <span class="sr-only">Edit</span>-->
                                            <!--</th>-->
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr
                                            v-for="(product, index) in products"
                                            :key="product.id"
                                            :class="[ index % 2 ? 'bg-white' : 'bg-grey-500' ]"
                                        >
                                            <td class="px-6 py-2 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ product.cyrName }}</div>
                                                <div v-if="product.latName" class="text-sm text-gray-500">
                                                    {{ product.latName }}
                                                </div>
                                            </td>

                                            <td class="px-6 py-2 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 mr-2">
                                                        <img class="h-10 w-10"
                                                             src="https://b2b.phoenixpharma.bg/bg/build/production/BgShop/resources/img/main/phoenix_circle_web.png"
                                                             alt=""/>
                                                    </div>
                                                    <div class="text-sm text-gray-900">Phoenix Pharma</div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-2 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ product.producerName }}</div>
                                            </td>

                                            <!--badge-->
                                            <!--<td class="px-6 py-2 whitespace-nowrap">-->
                                            <!--  <span-->
                                            <!--      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">-->
                                            <!--    Active-->
                                            <!--  </span>-->
                                            <!--</td>-->

                                            <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                                {{ product.salePrice }} лв.
                                            </td>

                                            <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                                {{ product.nhifSalePrice }} лв.
                                            </td>

                                            <!--button-->
                                            <!--<td class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium">-->
                                            <!--    <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>-->
                                            <!--</td>-->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
