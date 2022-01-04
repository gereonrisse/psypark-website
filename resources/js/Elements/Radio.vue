<template>
    <div @click="$emit('update:modelValue', id)">
        <input type="radio" class="align-middle" :id="id" :value="id" v-model="modelValue">
        <label :for="id" class="ml-2 align-middle">
            <slot></slot>
        </label>
    </div>
</template>

<script>
export default {
    name: "Radio",
    props: ['id', 'modelValue']
}
</script>
