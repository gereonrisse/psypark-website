<template>

    <page-header>
        <template v-slot:header>
            Dashboard
        </template>
    </page-header>

    <main-container>

        <div class="flex p-6 sm:p-0">
            Was soll hier eigentlich drauf?
        </div>

    </main-container>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Welcome from '@/Jetstream/Welcome.vue'
import PageHeader from "@/Elements/PageHeader";
import MainContainer from "@/Elements/MainContainer";

export default {
    layout: AppLayout,
    components: {
        MainContainer,
        PageHeader,
        Welcome,
    },
}
</script>
