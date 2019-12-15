<template>
    <div class="card p-4 mb-4" role="alert">
        <div class="flex">
            <div class="py-1 mr-4">
                <icon name="alert-circle" width="24" height="24"/>
            </div>
            <div>
                <p class="font-bold">
                    <slot name="title"/>
                </p>
                <p class="text-sm">
                    <slot name="body"/>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Alert"
}
</script>

<style scoped>

</style>
