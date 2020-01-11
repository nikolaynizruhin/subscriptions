<template>
    <aside class="gradient absolute flex-shrink-0 h-full sm:h-auto sm:transform-none sm:static sm:block w-64 p-4 text-gray-300 z-10 sm:z-auto">
        <router-link :to="{ name: 'dashboard' }" class="flex items-center mb-2 px-2 py-1 rounded hover:bg-gray-800 hover:text-white">
            <icon name="pie-chart" width="20" height="20"/>
            <span class="ml-4">Dashboard</span>
        </router-link>
    </aside>
</template>

<script>
export default {
    name: "Sidebar"
}
</script>
