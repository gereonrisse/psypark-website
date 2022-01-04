<template>
    <page-header>
        <template v-slot:header>
            Buchungen
        </template>
        <template v-slot:secondary>
            <table>
                <tr>
                    <td>Kasse:</td>
                    <td :class="[cash_balance < 0 ? 'text-red-600' : 'text-black', 'text-right']">{{ cash_balance }} €</td>
                </tr>
                <tr>
                    <td>Bank:</td>
                    <td :class="[bank_balance < 0 ? 'text-red-600' : 'text-black', 'text-right']">{{ bank_balance }} €</td>
                </tr>
                <tr class="font-bold">
                    <td class="pr-2">Gesamt:</td>
                    <td :class="[(Number(cash_balance) + Number(bank_balance)) < 0 ? 'text-red-600' : 'text-black', 'text-right']">
                        {{ (Number(cash_balance) + Number(bank_balance)).toFixed(2) }} €
                    </td>
                </tr>
            </table>
        </template>
    </page-header>

    <main-container>

        <div class="p-6 sm:p-0">

            <!-- download and new button -->
            <div class="flex">
                <div class="w-0 sm:w-full"></div>
                <jet-secondary-button type="button" class="flex-1 sm:flex-none break-all" @click="underConstruction">PDF herunterladen</jet-secondary-button>
                <jet-button type="button" class="flex-1 sm:flex-none ml-2" @click="$inertia.visit(route('posting-items.create'))">Neuer Eintrag</jet-button>
            </div>

            <!-- search and year filter -->
            <div class="flex mt-2">
                <search-input class="flex-grow" v-model="search" @change="reload"></search-input>
                <select
                    class="ml-2 hidden sm:inline-flex items-center pl-4 pr-7 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm
                    hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition"
                    v-model="selected_year" @change="reload">
                    <option value="all">alle Jahre</option>
                    <option v-for="available_year in available_years">{{ available_year }}</option>
                </select>
            </div>
        </div>

        <!-- table -->
        <div class="w-full sm:mt-8 overflow-hidden sm:rounded-lg shadow-lg">
            <div class="w-full">
                <table class="w-full">
                    <colgroup>
                        <col class="w-full">
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr class="text-xs sm:text-sm font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                        <th class="px-2 py-2 sm:px-4 sm:py-3 break-all">Beschreibung / Datum</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3">Person</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3">Betrag</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    <tr v-for="item in posting_items" class="text-gray-700 hover:bg-gray-100">
                        <td class="px-2 py-2 sm:px-4 sm:py-3 border">
                            <div class="text-sm">
                                <p class="font-semibold text-black break-all">{{ item.description }}</p>
                                <p class="text-xs text-gray-500">{{ formatDateTime(item.datetime) }}</p>
                            </div>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-sm border sm:whitespace-nowrap">{{ item.person }}</td>
                        <td class="px-2 py-1 sm:px-4 sm:py-3 text-xs sm:text-sm font-semibold border whitespace-nowrap">
                            <div class="flex flex-col">

                                <!-- amount -->
                                <div :class="((item.cash_amount ?? item.bank_amount) < 0) ? 'text-red-600' : 'text-black'">
                                    <span v-if="item.bank_amount === null">{{ item.cash_amount }} €</span>
                                    <span v-else-if="item.cash_amount === null">{{ item.bank_amount }} €</span>
                                    <span v-else>{{ Math.abs(item.bank_amount) }} €</span>
                                </div>

                                <!-- type -->
                                <div class="text-xs text-gray-500">
                                    <span v-if="item.bank_amount === null">Kasse</span>
                                    <span v-else-if="item.cash_amount === null">Bank</span>
                                    <span v-else-if="item.cash_amount > item.bank_amount">Transfer Bank -> Kasse</span>
                                    <span v-else>Transfer Kasse -> Bank</span>
                                </div>

                            </div>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 border">
                            <button type="button" @click="$inertia.visit(route('posting-items.show', { posting_item: item }))"
                                    class="inline-flex items-center px-2 py-1 sm:px-4 sm:py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700
                                     uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200
                                      active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                                Details
                            </button>
                        </td>
                    </tr>

                    <!-- sum row -->
                    <tr class="font-bold bg-gray-100">
                        <td colspan="2" class="px-2 py-2 sm:px-4 sm:py-3 border">
                            Summe
                        </td>
                        <td colspan="2"
                            :class="[itemSum < 0 ? 'text-red-600' : 'text-black' , 'px-2 py-1 sm:px-4 sm:py-3 text-xs sm:text-sm border whitespace-nowrap']">
                            {{ itemSum }} €
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </main-container>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import PageHeader from "@/Elements/PageHeader";
import MainContainer from "@/Elements/MainContainer";
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import {ref} from 'vue'
import SearchInput from "@/Elements/SearchInput";
import moment from 'moment'

export default {
    name: "Index",
    components: {
        SearchInput,
        MainContainer,
        PageHeader,
        JetButton,
        JetSecondaryButton,
        JetInput
    },
    layout: AppLayout,
    props: ['posting_items', 'cash_balance', 'bank_balance', 'available_years'],
    setup(props) {
        const url_params = new URLSearchParams(window.location.search)

        const search = ref(url_params.get('search'));
        const selected_year = ref(url_params.get('year') ?? props.available_years[0]);

        return {
            search,
            selected_year
        }
    },
    methods: {
        underConstruction() {
            alert('💩')
        },
        reload() {
            let query = {year: this.selected_year}
            if (this.search) query.search = this.search

            this.$inertia.visit(route('posting-items.index', query))
        },
        formatDateTime(datetime) {
            return moment(datetime).locale('de').format('L LT')
        }
    },
    computed: {
        itemSum() {
            let sum = 0;
            for (const item of this.posting_items)
            {
                if (item.bank_amount === null) sum += parseFloat(item.cash_amount);
                if (item.cash_amount === null) sum += parseFloat(item.bank_amount);
            }
            return sum.toFixed(2);
        }
    }
}
</script>
