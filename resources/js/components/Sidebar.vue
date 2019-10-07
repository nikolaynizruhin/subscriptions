<template>
    <aside class="gradient absolute h-full sm:h-auto sm:transform-none sm:static sm:block w-56 p-4 text-gray-300 z-10 sm:z-auto">
        <router-link :to="{ name: 'invoices.index' }" class="flex items-center mb-2 px-2 py-1 rounded hover:bg-gray-800 hover:text-white">
            <icon name="file-text" width="20" height="20"/>
            <span class="ml-4">Invoices</span>
        </router-link>
    </aside>
</template>

<script>
import Icon from "./Icon";

export default {
    name: "Sidebar",
    components: { Icon },
}
</script>
