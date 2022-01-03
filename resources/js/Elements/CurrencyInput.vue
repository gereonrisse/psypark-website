<template>
    <div class="flex flex-row">

        <input
            class="flex-grow border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            ref="inputRef"
            type="text"
            v-model="formattedValue"
        >

        <jet-secondary-button class="flex-none ml-2" type="button" @click="invert">
            +/-
        </jet-secondary-button>

    </div>
</template>

<script>
import { useCurrencyInput } from 'vue-currency-input';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';

export default {
    name: 'CurrencyInput',
    components: {
        JetSecondaryButton
    },
    props: {
        modelValue: Number, // Vue 2: value
        options: Object
    },
    setup (props) {
        const { formattedValue, inputRef } = useCurrencyInput(props.options)

        return { inputRef, formattedValue }
    },
    methods: {
        invert() {
            this.$emit('update:modelValue', -this.modelValue);

            // hack for adjusting the shown value because they patched out setValue in vue-currency-input v2
            if (this.formattedValue.charAt(0) === '-') this.formattedValue = this.formattedValue.substring(1);
            else this.formattedValue = '-' + this.formattedValue;
        }
    }
}
</script>
