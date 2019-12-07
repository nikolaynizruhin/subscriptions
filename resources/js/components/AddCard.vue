<template>
    <modal @close="close">
        <div class="w-108">
            <h3 class="text-xl p-6">Add Card</h3>
            <form @submit.prevent="add">
                <div class="px-6 pb-6">
                    <div id="card-element" class="form-input w-full" :class="{ 'is-invalid': error }"></div>
                    <p class="text-red-500 text-sm mt-1" v-if="error" v-text="error"/>
                </div>
                <div class="bg-gray-100 px-6 py-4 rounded-b">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto mr-2" id="card-button" :disabled="loading">
                        <span v-if="loading" class="spinner"/>
                        {{ loading ? 'Loading...' : 'Add' }}
                    </button>
                    <button @click="close" class="btn btn-link">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
import { mapMutations, mapActions } from 'vuex'
import Modal from "./Modal"

export default {
    name: "AddCard",
    components: { Modal },
    data () {
        return {
            stripe: null,
            card: null,
            error: '',
            loading: false,
        }
    },
    async mounted () {
        this.$nextTick(() => this.init())
    },
    methods: {
        ...mapMutations(['setFlash']),
        ...mapActions(['addPaymentMethod']),
        init () {
            this.stripe = Stripe(app.stripe.key)

            const elements = this.stripe.elements()

            const style = {
                base: {
                    color: '#2d3748',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#a0aec0'
                    }
                },
                invalid: {
                    color: '#f56565',
                    iconColor: '#f56565'
                }
            }

            this.card = elements.create('card', { style })

            this.card.mount('#card-element')

            this.card.addEventListener('change', ({ error }) => this.error = error ? error.message : null);
        },
        close () {
            this.$emit('close')
        },
        clearError () {
            this.error = ''
        },
        async add () {
            this.clearError()

            this.loading = true

            const { data } = await axios.post('/api/setup-intents')

            const { setupIntent, error } = await this.stripe.confirmCardSetup(
                data.client_secret,
                { payment_method: { card: this.card }}
            )

            if (error) {
                this.loading = false

                return this.error = error.message
            }

            await this.addPaymentMethod(setupIntent.payment_method)

            this.card.clear()

            this.setFlash({ message: 'Credit card successfully added!' })

            this.close()

            this.loading = false
        }
    }
}
</script>

<style scoped>

</style>
