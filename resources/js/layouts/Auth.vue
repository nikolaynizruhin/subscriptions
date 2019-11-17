<template>
    <div class="flex flex-col items-center min-h-screen px-2 sm:px-0">
        <div class="flex items-center py-12 sm:pt-20">
            <icon name="layers" width="32" height="32"/>
            <h3 class="ml-3 text-2xl tracking-wide">{{ title }}</h3>
        </div>
        <div class="w-full max-w-sm card">
            <slot/>
        </div>
    </div>
</template>

<script>
import Icon from '../components/Icon'
import title from '../mixins/title'

export default {
    name: "AuthLayout",
    components: { Icon },
    mixins: [title],
}
</script>

<style scoped>

</style>
