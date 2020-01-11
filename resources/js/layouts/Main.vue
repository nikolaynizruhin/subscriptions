<template>
    <div class="flex flex-col min-h-screen">
        <header class="flex shadow">
            <div class="bg-gray-800 border-b border-gray-800 w-full sm:w-64 px-6 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <icon name="layers" class="text-white" width="32" height="32"/>
                    <h3 class="ml-3 text-white text-xl tracking-wide">{{ title }}</h3>
                </div>
                <button @click="toggle" type="button" class="sm:hidden text-white focus:outline-none">
                    <icon v-if="isOpen" name="x" width="32" height="32"/>
                    <icon v-else name="menu" width="32" height="32"/>
                </button>
            </div>
            <div class="hidden border-gray-800 bg-gray-800 sm:border-gray-300 sm:bg-white border-b flex-grow px-6 py-3 sm:flex justify-end">
                <account-dropdown/>
            </div>
        </header>
        <div class="flex flex-grow">
            <sidebar :class="isOpen ? 'slide-in' : 'slide-out'"/>
            <main class="flex-grow p-6 relative sm:static">
                <div v-if="isOpen" @click="close" class="sm:hidden absolute inset-0 bg-black opacity-25"></div>
                <verify-alert v-if="!user.email_verified_at"/>
                <slot/>
            </main>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import AccountDropdown from "../components/AccountDropdown";
import Sidebar from "../components/Sidebar";
import VerifyAlert from "../components/alerts/VerifyAlert";
import toggle from "../mixins/toggle";
import title from "../mixins/title";

export default {
    name: "MainLayout",
    components: {
        AccountDropdown,
        Sidebar,
        VerifyAlert,
    },
    mixins: [toggle, title],
    computed: {
        ...mapState(['user']),
    },
}
</script>
