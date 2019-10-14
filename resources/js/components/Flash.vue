<template>
    <transition name="slide-up">
        <div
            v-if="flash.message"
            class="fixed bottom-0 right-0 m-6 text-white px-4 py-2 shadow rounded"
            :class="{ 'bg-green-500': flash.type === 'success', 'bg-red-600': flash.type === 'error' }"
            @mouseover="clearTimeout"
            @mouseleave="close"
        >
            {{ flash.message }}
        </div>
    </transition>
</template>

<script>
import { mapState, mapMutations } from 'vuex'

export default {
    name: "Flash",
    data () {
        return {
            timeout: null
        }
    },
    computed: mapState(['flash']),
    methods: {
        ...mapMutations(['clearFlash']),
        close () {
            this.timeout = setTimeout(() => this.clearFlash(), 5000)
        },
        clearTimeout () {
            clearTimeout(this.timeout)
        }
    },
    watch: {
        'flash.message' (message) {
            if (message) {
                this.clearTimeout()
                this.close()
            }
        }
    }
}
</script>

<style scoped>
.slide-up-enter-active, .slide-up-leave-active {
    opacity: 1;
    transform: translateY(0%);
    transition: all .3s;
}

.slide-up-enter, .slide-up-leave-to {
    opacity: 0;
    transform: translateY(100%);
    transition: all .3s;
}
</style>
