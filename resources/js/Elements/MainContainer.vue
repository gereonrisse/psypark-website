<template>
    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sm:p-6 sm:px-20 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <slot></slot>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    name: "MainContainer"
}
</script>
