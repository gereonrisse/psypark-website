<template>
    <page-header>
        <template v-slot:header>
            Buchung bearbeiten
        </template>
    </page-header>

    <thin-container>
        <form class="p-6 sm:p-0" @submit.prevent="form.put(route('posting-items.update', {posting_item: form}))">

            <!-- Description -->
            <div>
                <jet-label for="description" value="Beschreibung"/>
                <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description"/>
                <jet-input-error :message="form.errors.description" class="mt-2"/>
            </div>

            <!-- Person -->
            <div class="mt-4">
                <jet-label for="person" value="Person"/>
                <jet-input id="person" type="text" class="mt-1 block w-full" v-model="form.person"/>
                <jet-input-error :message="form.errors.person" class="mt-2"/>
            </div>

            <!-- Amount -->
            <div class="mt-4">
                <jet-label for="amount" value="Betrag"/>

                <currency-input :class="[form.amount < 0 ? 'text-red-600' : 'text-black' , 'mt-1 block w-full']" v-model="form.amount"
                                :options="{ currency: 'EUR', autoDecimalDigits: true, precision: 2 }">
                </currency-input>

                <jet-input-error :message="form.errors.amount" class="mt-2"/>
            </div>

            <!-- Datetime -->
            <div class="mt-4">
                <jet-label for="datetime" value="Zeitpunkt"/>
<!--                <jet-input id="datetime" type="datetime-local" class="mt-1 block w-full" v-model="form.datetime"/>-->

                <div class="flex">
                    <jet-input id="date" type="date" class="mt-1 block w-full" v-model="form.date"/>
                    <jet-input id="time" type="time" class="mt-1 ml-2 block w-full" v-model="form.time"/>
                </div>

                <jet-input-error :message="form.errors.date" class="mt-2"/>
                <jet-input-error :message="form.errors.time" class="mt-2"/>
            </div>

            <!-- Notes -->
            <div class="mt-4">
                <jet-label for="notes" value="Anmerkungen (optional)"/>
                <jet-textarea id="notes" type="text" class="mt-1 block w-full" v-model="form.notes"></jet-textarea>
                <jet-input-error :message="form.errors.notes" class="mt-2"/>
            </div>

            <!-- File -->
            <div class="mt-4">
                <jet-label for="file" value="Datei (optional)"/>
                <div class="text-sm text-gray-500">das muss ich erst noch programmieren 💩</div>
                <!--                <jet-input id="person" type="file" class="mt-1 block w-full" v-model="form.person"/>-->
                <jet-input-error :message="form.errors.file" class="mt-2"/>
            </div>

            <jet-button class="mt-4 w-full" type="submit">aktualisieren</jet-button>

        </form>
    </thin-container>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import PageHeader from "@/Elements/PageHeader";
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetTextarea from '@/Jetstream/Textarea.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import {useForm} from '@inertiajs/inertia-vue3'
import ThinContainer from "@/Elements/ThinContainer";
import CurrencyInput from "@/Elements/CurrencyInput";
import moment from "moment";

export default {
    name: "Edit",
    components: {
        CurrencyInput,
        ThinContainer,
        PageHeader,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetTextarea,
        JetLabel,
        JetInputError
    },
    layout: AppLayout,
    props: ['posting_item'],
    setup(props) {
        const form = useForm({
            amount: props.posting_item.amount,
            description: props.posting_item.description,
            created_at: props.posting_item.created_at,
            deleted_at: props.posting_item.deleted_at,
            file_hash: props.posting_item.file_hash,
            id: props.posting_item.id,
            notes: props.posting_item.notes,
            person: props.posting_item.person,
            updated_at: props.posting_item.updated_at,
            user_id: props.posting_item.user_id,
            date: moment(props.posting_item.datetime).format('YYYY-MM-DD'),
            time: moment(props.posting_item.datetime).format('HH:mm')
        });

        return {
            form
        }
    },
}
</script>
