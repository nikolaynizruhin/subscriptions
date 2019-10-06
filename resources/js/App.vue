<template>
    <component :is="layout">
        <router-view/>
    </component>
</template>

<script>
export default {
    name: "App",
    computed: {
        layout () {
            return `${this.$route.meta.layout || 'default'}-layout`
        }
    }
}
</script>

<style scoped>

</style>
