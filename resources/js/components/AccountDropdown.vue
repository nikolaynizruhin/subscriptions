<template>
    <div class="hidden sm:block relative">
        <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0"></div>
        <button type="button" class="relative z-10 flex items-center focus:outline-none" @click="isOpen = !isOpen">
            <img src="https://github.com/adamwathan.png" alt="Adam Wathan's avatar" class="rounded-full h-8 w-8">
            <span class="ml-2">Adam Wathan</span>
            <svg class="ml-1 h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"/>
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
