<template>
    <div class="flex flex-col min-h-screen">
        <header class="flex shadow">
            <div class="bg-gray-800 border-b border-gray-800 w-full sm:w-56 px-6 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <img src="/images/logo.svg" class="h-8 w-10" alt="Logo">
                    <h3 class="ml-3 text-white text-xl tracking-wide">Invoice</h3>
                </div>
                <button @click="isOpen = !isOpen" type="button" class="sm:hidden text-white focus:outline-none">
                    <svg v-if="isOpen" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="hidden border-gray-800 bg-gray-800 sm:border-gray-300 sm:bg-white border-b flex-grow px-6 py-3 sm:flex justify-end">
                <account-dropdown/>
            </div>
        </header>
        <div class="flex flex-grow">
            <sidebar :class="isOpen ? 'absolute h-full' : 'hidden'"/>
            <main class="flex-grow p-6 relative sm:static">
                <div v-if="isOpen" @click="isOpen = false" class="absolute inset-0 bg-gray-500 opacity-50"></div>
                <slot/>
            </main>
        </div>
    </div>
</template>

<script>
import AccountDropdown from "../components/AccountDropdown";
import Sidebar from "../components/Sidebar";
import toggle from "../mixins/toggle";

export default {
    name: "MainLayout",
    components: {
        AccountDropdown,
        Sidebar,
    },
    mixins: [toggle],
}
</script>
