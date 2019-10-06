<template>
    <div class="flex flex-col min-h-screen">
        <header class="flex shadow">
            <div class="bg-gray-800 border-b border-gray-800 w-full sm:w-56 px-6 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <img src="/images/logo.svg" class="h-8 w-10" alt="Logo">
                    <h3 class="ml-3 text-white text-xl tracking-wide">Invoice</h3>
                </div>
                <button @click="isOpenMenu = !isOpenMenu" type="button" class="sm:hidden text-white focus:outline-none">
                    <svg v-if="isOpenMenu" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="hidden border-gray-800 bg-gray-800 sm:border-gray-300 sm:bg-white border-b flex-grow px-6 py-3 sm:flex justify-end">
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
            </div>
        </header>
        <div class="flex flex-grow">
            <aside :class="isOpenMenu ? 'absolute h-full' : 'hidden'" class="gradient sm:block w-56 p-4 text-gray-300 z-10 sm:z-auto">
                <router-link :to="{ name: 'invoices.index' }" class="flex items-center mb-2 px-2 py-1 rounded hover:bg-gray-800 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span class="ml-4">Invoices</span>
                </router-link>
            </aside>
            <main class="flex-grow p-6 relative sm:static">
                <div v-if="isOpenMenu" @click="isOpenMenu = false" class="absolute inset-0 bg-gray-500 opacity-50"></div>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
export default {
    name: "Main",
    data () {
        return {
            isOpen: false,
            isOpenMenu: false
        }
    },
    created () {
        document.addEventListener('keydown', this.handleEscape)
    },
    beforeDestroy () {
        document.removeEventListener('keydown', this.handleEscape)
    },
    methods: {
        handleEscape (event) {
            if (event.key === 'Escape') {
                this.isOpen = false
                this.isOpenMenu = false
            }
        },
        logout () {
            localStorage.removeItem('token')
            this.$router.push('/login')
        }
    }
}
</script>
