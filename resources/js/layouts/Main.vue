<template>
    <div class="flex flex-col min-h-screen">
        <header class="flex shadow">
            <div class="bg-gray-800 border-b border-gray-800 w-full sm:w-56 px-6 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <img src="/images/logo.svg" class="h-8 w-10" alt="Logo">
                    <h3 class="ml-3 text-white text-xl tracking-wide">Invoice</h3>
                </div>
                <button @click="isOpen = !isOpen" type="button" class="sm:hidden text-white focus:outline-none">
                    <icon v-if="isOpen" name="x" width="32" height="32"/>
                    <icon v-else name="menu" width="32" height="32"/>
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
import Icon from "../components/Icon";

export default {
    name: "MainLayout",
    components: {
        AccountDropdown,
        Icon,
        Sidebar,
    },
    mixins: [toggle],
}
</script>
