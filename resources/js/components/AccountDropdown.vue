<template>
    <div class="hidden sm:block relative">
        <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0"></div>
        <button type="button" class="relative z-10 flex items-center focus:outline-none" @click="isOpen = !isOpen">
            <img src="https://github.com/adamwathan.png" alt="Adam Wathan's avatar" class="rounded-full h-8 w-8">
            <span class="ml-2">Adam Wathan</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        <div v-if="isOpen" class="absolute right-0 left-auto bg-white border shadow py-2 rounded">
            <a href="#" @click.prevent="logout" class="block px-6 py-1 hover:bg-gray-300">Logout</a>
        </div>
    </div>
</template>

<script>
import toggle from "../mixins/toggle";

export default {
    name: "AccountDropdown",
    mixins: [toggle],
    methods: {
        logout () {
            localStorage.removeItem('token')
            this.$router.push('/login')
        }
    }
}
</script>

<style scoped>

</style>
