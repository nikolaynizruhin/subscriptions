<template>
    <modal @close="close">
        <div class="max-w-lg w-full">
            <div class="p-6">
                <h3 class="text-xl mb-4">Remove Card</h3>
                <p>
                    Are you sure you want to remove the card
                    <span v-for="i in 4" :key="i">&bull;</span>
                    {{ paymentMethod.card.last4 }}?
                </p>
            </div>
            <div class="bg-gray-100 px-6 py-4 rounded-b">
                <button type="button" @click="remove" class="btn btn-danger mr-2" :disabled="loading">
                    <span v-if="loading" class="spinner"/>
                    {{ loading ? 'Loading...' : 'Remove' }}
                </button>
                <button @click="close" class="btn btn-link">
                    Cancel
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import { mapMutations, mapActions } from 'vuex'
import Modal from "./Modal"

export default {
    name: "RemoveCard",
    props: ['paymentMethod'],
    components: { Modal },
    data () {
        return {
            loading: false
        }
    },
    methods: {
        ...mapMutations(['setFlash']),
        ...mapActions(['removePaymentMethod']),
        close () {
            this.$emit('close')
        },
        async remove () {
            this.loading = true

            await this.removePaymentMethod(this.paymentMethod)

            this.setFlash({ message: 'Payment method removed!' })

            this.loading = false

            this.close()
        }
    }
}
</script>

<style scoped>

</style>
