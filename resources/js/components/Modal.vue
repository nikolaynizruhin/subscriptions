<template>
    <portal to="modal">
        <div class="fixed inset-0 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-25" @click="close"></div>
            <div class="relative card">
                <slot/>
            </div>
        </div>
    </portal>
</template>

<script>
export default {
    name: "Modal",
    created () {
        const handleEscape = event => {
            if (event.key === 'Escape') {
                this.close()
            }
        }

        document.addEventListener('keydown', handleEscape)

        document.body.classList.add('overflow-hidden')

        this.$once('hook:beforeDestroy', () => {
            document.removeEventListener('keydown', handleEscape)

            document.body.classList.remove('overflow-hidden')
        })
    },
    methods: {
        close () {
            this.$emit('close')
        }
    }
}
</script>

<style scoped>

</style>
