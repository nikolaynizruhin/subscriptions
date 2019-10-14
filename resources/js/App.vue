<template>
    <component :is="layout">
        <router-view/>
        <flash/>
    </component>
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
