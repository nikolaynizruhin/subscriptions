<template>
    <div>
        <nav class="flex border-b mb-6">
            <router-link :to="{ name: 'settings.profile' }" class="flex items-center px-4 py-3 -mb-px border-b-2 border-transparent hover:border-gray-500 text-gray-500">
                <icon class="mr-1" name="user" width="18" height="18"/>
                Profile
            </router-link>
            <router-link :to="{ name: 'settings.security' }" class="flex items-center px-4 py-3 -mb-px border-b-2 border-transparent hover:border-gray-500 text-gray-500">
                <icon class="mr-1" name="shield" width="18" height="18"/>
                Security
            </router-link>
            <router-link :to="{ name: 'settings.subscription' }" class="flex items-center px-4 py-3 -mb-px border-b-2 border-transparent hover:border-gray-500 text-gray-500">
                <icon class="mr-1" name="dollar-sign" width="18" height="18"/>
                Subscription
            </router-link>
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
