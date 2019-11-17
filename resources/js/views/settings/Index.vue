<template>
    <div>
        <nav class="flex border-b mb-6">
            <router-link :to="{ name: 'settings.profile' }" class="inline-block px-4 py-3 -mb-px border-b-2 border-transparent hover:border-gray-500 text-gray-500">Profile</router-link>
            <router-link :to="{ name: 'settings.security' }" class="inline-block px-4 py-3 -mb-px border-b-2 border-transparent hover:border-gray-500 text-gray-500">Security</router-link>
            <router-link :to="{ name: 'settings.subscription' }" class="inline-block px-4 py-3 -mb-px border-b-2 border-transparent hover:border-gray-500 text-gray-500">Subscription</router-link>
        </nav>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "Settings"
}
</script>

<style scoped>

</style>
