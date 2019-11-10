<template>
    <div class="hidden sm:block relative">
        <div v-if="isOpen" @click="close" class="fixed inset-0"></div>
        <button type="button" class="relative z-10 flex items-center focus:outline-none" @click="toggle">
            <img src="https://github.com/adamwathan.png" alt="Adam Wathan's avatar" class="rounded-full h-8 w-8">
            <span class="ml-2">{{ user.name }}</span>
            <icon name="chevron-down" class="ml-1" width="16" height="16"/>
        </button>
        <div v-if="isOpen" class="absolute right-0 left-auto w-32 bg-white border shadow py-1 rounded">
            <router-link :to="{ name: 'settings' }" @click.native="close" class="flex items-center px-3 py-1 hover:bg-gray-300">
                <icon name="settings" width="18" height="18"/>
                <span class="ml-2">Settings</span>
            </router-link>
            <a href="#" @click.prevent="logout" class="flex items-center px-3 py-1 hover:bg-gray-300">
                <icon name="log-out" width="18" height="18"/>
                <span class="ml-2">Logout</span>
            </a>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import toggle from "../mixins/toggle"
import Icon from "./Icon"

export default {
    name: "AccountDropdown",
    components: { Icon },
    mixins: [toggle],
    computed: mapState(['user']),
    methods: {
        ...mapMutations(['setUser']),
        logout () {
            delete localStorage.token

            this.setUser({})

            this.$router.push('/login')
        }
    }
}
</script>

<style scoped>

</style>
