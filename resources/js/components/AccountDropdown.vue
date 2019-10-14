<template>
    <div class="hidden sm:block relative">
        <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0"></div>
        <button type="button" class="relative z-10 flex items-center focus:outline-none" @click="isOpen = !isOpen">
            <img src="https://github.com/adamwathan.png" alt="Adam Wathan's avatar" class="rounded-full h-8 w-8">
            <span class="ml-2">{{ user.name }}</span>
            <icon name="chevron-down" class="ml-1" width="16" height="16"/>
        </button>
        <div v-if="isOpen" class="absolute right-0 left-auto bg-white border shadow py-2 rounded">
            <a href="#" @click.prevent="logout" class="block px-6 py-1 hover:bg-gray-300">Logout</a>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import toggle from "../mixins/toggle"
import Icon from "./Icon"

export default {
    name: "AccountDropdown",
    components: { Icon },
    mixins: [toggle],
    computed: mapState(['user']),
    methods: {
        ...mapActions(['getUser']),
        logout () {
            delete localStorage.token
            this.$router.push('/login')
        }
    },
    created () {
        this.getUser()
    }
}
</script>

<style scoped>

</style>
