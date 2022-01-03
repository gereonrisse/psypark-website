<template>
    <page-header>
        <template v-slot:header>
            Neue Buchung
        </template>
    </page-header>

    <thin-container>
        <form class="p-6 sm:p-0" @submit.prevent="form.post(route('posting-items.store'))">

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

                <currency-input :class="[form.amount < 0 ? 'text-red-600' : 'text-black' , 'mt-1 block w-full flex-grow']" v-model="form.amount"
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

            <jet-button class="mt-4 w-full" type="submit">Speichern</jet-button>

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
import {usePage} from '@inertiajs/inertia-vue3'
import ThinContainer from "@/Elements/ThinContainer";
import CurrencyInput from "@/Elements/CurrencyInput";

export default {
    name: "Create",
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
    setup() {
        const now = new Date();

        const form = useForm({
            description: null,
            person: usePage().props.value.user.name,
            amount: -20.00,
            date: now.toISOString().split('T')[0],
            time: ("0" + now.getHours()).slice(-2)   + ":" + ("0" + now.getMinutes()).slice(-2),
            notes: null,
            file: null
        });

        return {
            form
        }
    },
}
</script>
