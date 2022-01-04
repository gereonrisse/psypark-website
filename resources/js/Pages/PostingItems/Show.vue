<template>
    <page-header>
        <template v-slot:header>
            {{ posting_item.description }}
        </template>
    </page-header>

    <thin-container>

        <!-- edit and delete button -->
        <div class="flex p-6 sm:p-0">
            <div class="w-0 sm:w-full"></div>
            <jet-danger-button type="button" class="flex-1 sm:flex-none" @click="showDestroyModal = true">löschen</jet-danger-button>
            <jet-button type="button" class="flex-1 sm:flex-none ml-2" @click="$inertia.visit(route('posting-items.edit', {posting_item: posting_item}))">
                bearbeiten
            </jet-button>
        </div>

        <div class="px-6 sm:px-2 mt-2 sm:mt-10 mb-6 sm:mb-0">

            <table class="w-full">
                <tbody>
                <tr>
                    <td class="font-semibold">
                        Betrag
                    </td>
                    <td :class="[posting_item.cash_amount < 0 ? 'text-red-600' : 'text-black', 'text-right']">
                        {{ posting_item.cash_amount }} €
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">
                        Beschreibung
                    </td>
                    <td class="text-right text-gray-700">
                        {{ posting_item.description }}
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">
                        Person
                    </td>
                    <td class="text-right text-gray-700">
                        {{ posting_item.person }}
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">
                        Zeitpunkt
                    </td>
                    <td class="text-right text-gray-700">
                        {{ formatDateTime(posting_item.datetime) }}
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="mt-8">
                <h4 class="font-semibold">Anmerkungen</h4>
                <p class="text-gray-700">{{ posting_item.notes }}</p>
            </div>

            <div class="mt-8">
                <h4 class="font-semibold">Datei</h4>
                <p class="w-full h-48 bg-gray-300 text-center align-middle">
                    💩
                </p>
            </div>

            <table class="w-full mt-8 text-sm">
                <tbody>
                <tr>
                    <td class="text-gray-700">
                        Angelegt von
                    </td>
                    <td class="text-right text-gray-500">
                        {{ user_name }}
                    </td>
                </tr>
                <tr>
                    <td class="text-gray-700">
                        Erstellt
                    </td>
                    <td class="text-right text-gray-500">
                        {{ formatDateTime(posting_item.created_at) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-gray-700">
                        Zuletzt geändert
                    </td>
                    <td class="text-right text-gray-500">
                        {{ formatDateTime(posting_item.updated_at) }}
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

    </thin-container>

    <dialog-modal :show="showDestroyModal" @close="showDestroyModal = false">
        <template v-slot:title>
            Buchung löschen
        </template>
        <template v-slot:content>
            Wie sicher bist du dir, dass du das tun willst?
        </template>
        <template v-slot:footer>
            <div class="flex">
                <div class="w-0 sm:w-full"></div>
                <jet-secondary-button type="button" class="flex-1 sm:flex-none" @click="showDestroyModal = false">
                    abbrechen
                </jet-secondary-button>
                <jet-danger-button type="button" class="flex-1 sm:flex-none ml-2" @click="$inertia.delete(route('posting-items.destroy', {posting_item: posting_item}))">
                    Ja
                </jet-danger-button>
            </div>
        </template>
    </dialog-modal>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import PageHeader from "@/Elements/PageHeader";
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetDangerButton from '@/Jetstream/DangerButton.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetTextarea from '@/Jetstream/Textarea.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import ThinContainer from "@/Elements/ThinContainer";
import CurrencyInput from "@/Elements/CurrencyInput";
import moment from "moment";
import {ref} from 'vue'
import DialogModal from "@/Jetstream/DialogModal";

export default {
    name: "Show",
    components: {
        DialogModal,
        CurrencyInput,
        ThinContainer,
        PageHeader,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
        JetInput,
        JetTextarea,
        JetLabel,
        JetInputError
    },
    layout: AppLayout,
    props: {
        posting_item: Object,
        user_name: String
    },
    setup() {
        return {
            showDestroyModal: ref(false)
        }
    },
    methods: {
        formatDateTime(datetime)
        {
            return moment(datetime).locale('de').format('L LT')
        }
    }
}
</script>
