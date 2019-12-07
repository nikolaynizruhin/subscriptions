<template>
    <div class="bg-gray-100 text-gray-800 antialiased">
        <component :is="layout">
            <router-view/>
        </component>
        <portal-target name="modal"></portal-target>
        <flash/>
    </div>
</template>

<script>
import Flash from "./components/Flash";

export default {
    name: "App",
    components: { Flash },
    computed: {
        layout () {
            return `${this.$route.meta.layout || 'default'}-layout`
        }
    }
}
</script>

<style scoped>

</style>
