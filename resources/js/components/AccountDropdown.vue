<template>
    <dropdown class="hidden sm:block">
        <template #button="{ isOpen }">
            <img src="https://github.com/nikolaynizruhin.png" alt="Adam Wathan's avatar" class="rounded-full h-8 w-8">
            <span dusk="account-button" class="ml-2">{{ user.name }}</span>
            <span v-if="isOpen" key="up">
                <icon name="chevron-up" class="ml-1" width="16" height="16"/>
            </span>
            <span v-else key="down">
                <icon name="chevron-down" class="ml-1" width="16" height="16"/>
            </span>
        </template>
        <template #dropdown="{ close }">
            <div class="w-32">
                <router-link :to="{ name: 'settings.profile' }" @click.native="close" class="flex items-center px-3 py-1 hover:bg-gray-300">
                    <icon name="settings" width="18" height="18"/>
                    <span class="ml-2">Settings</span>
                </router-link>
                <a href="#" @click.prevent="signOut" class="flex items-center px-3 py-1 hover:bg-gray-300">
                    <icon name="log-out" width="18" height="18"/>
                    <span class="ml-2">Logout</span>
                </a>
            </div>
        </template>
    </dropdown>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import Dropdown from "./Dropdown";

export default {
    name: "AccountDropdown",
    components: { Dropdown },
    computed: mapState(['user']),
    methods: {
        ...mapMutations(['logout']),
        signOut () {
            this.logout()

            this.$router.push('/login')
        }
    }
}
</script>

<style scoped>

</style>
