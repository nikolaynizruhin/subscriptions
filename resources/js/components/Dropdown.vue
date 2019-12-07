<template>
    <div class="relative">
        <div v-if="isOpen" @click="close" class="fixed inset-0"></div>
        <button type="button" class="relative z-10 flex items-center focus:outline-none" @click="toggle">
            <slot name="button" :is-open="isOpen"/>
        </button>
        <div v-if="isOpen" class="absolute right-0 left-auto bg-white border shadow py-1 mt-2 rounded">
            <slot name="dropdown" :close="close"/>
        </div>
    </div>
</template>

<script>
import toggle from "../mixins/toggle"

export default {
    name: "Dropdown",
    mixins: [toggle]
}
</script>

<style scoped>

</style>
