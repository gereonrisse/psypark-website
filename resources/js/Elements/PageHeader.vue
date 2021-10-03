<template>
  <header class="bg-white shadow">
    <div class="sm:flex max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="sm:flex-grow font-semibold text-xl text-gray-800 leading-tight">
            <slot name="header"></slot>
        </h2>
        <div class="mt-2 sm:mt-0">
            <slot name="secondary"></slot>
        </div>
    </div>
  </header>
</template>
<script>
export default {
  name: 'PageHeader'
}
</script>
