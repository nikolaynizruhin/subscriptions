<template>
    <div class="w-full card">
        <div v-if="loading" class="flex items-center justify-center p-6">
            <span class="spinner text-gray-500 w-6 h-6"/>
        </div>
        <table v-else class="table-auto w-full">
            <tbody>
                <tr
                    v-for="(paymentMethod, index) in paymentMethods"
                    :key="index"
                    :class="{ 'border-b': isNotLastPaymentMethod(index) }"
                >
                    <td class="p-6">
                        <div class="flex items-center">
                            <icon name="credit-card" class="mr-2" width="24" height="24"/>
                            <span v-for="i in 4" :key="i">&bull;</span>
                            <span class="ml-1">{{ paymentMethod.card.last4 }}</span>
                            <span class="ml-3 hidden sm:inline">{{ paymentMethod.card.exp_month }}/{{ paymentMethod.card.exp_year }}</span>
                        </div>
                    </td>
                    <td class="p-6">
                        <div class="flex items-center justify-end">
                            <span
                                v-if="isDefault(paymentMethod)"
                                class="rounded-full bg-green-200 text-green-800 px-2 py-1 font-semibold text-xs mr-6"
                            >
                                Default
                            </span>
                            <span @click="showModal(paymentMethod)" class="mr-2">
                                <icon name="trash-2" key="trash-2" class="text-gray-500 cursor-pointer hover:text-red-600" width="20" height="20"/>
                            </span>
                            <dropdown :ref="paymentMethod.id">
                                <template #button>
                                    <icon name="more-vertical" key="more-vertical" class="text-gray-500 hover:text-blue-500" width="20" height="20"/>
                                </template>
                                <template #dropdown>
                                    <div class="w-32">
                                        <button
                                            @click="setAsDefault(paymentMethod)"
                                            :class="{ 'opacity-75 cursor-not-allowed text-gray-500': isDisabled(paymentMethod) }"
                                            class="w-full px-3 py-1 hover:bg-gray-300 focus:outline-none"
                                            :disabled="isDisabled(paymentMethod)"
                                        >
                                            <span v-if="loadings[paymentMethod.id]" class="spinner"/>
                                            {{ loadings[paymentMethod.id] ? 'Loading...' : 'Set As Default' }}
                                        </button>
                                    </div>
                                </template>
                            </dropdown>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <remove-card v-if="isOpen" :payment-method="paymentMethod" @close="closeModal"/>
    </div>
</template>

<script>
import { mapMutations, mapState, mapActions } from 'vuex'
import Dropdown from "./Dropdown"
import RemoveCard from "./RemoveCard"

export default {
    name: "Cards",
    components: { RemoveCard, Dropdown },
    data () {
        return {
            paymentMethod: {},
            customer: {},
            loadings: {},
            loading: true,
            isOpen: false,
        }
    },
    computed: mapState(['paymentMethods']),
    methods: {
        ...mapMutations(['setFlash']),
        ...mapActions(['getPaymentMethods']),
        isDefault (paymentMethod) {
            return paymentMethod.id === this.customer.invoice_settings.default_payment_method
        },
        isDisabled (paymentMethod) {
            return this.isDefault(paymentMethod) || this.loadings[paymentMethod.id]
        },
        isNotLastPaymentMethod (index) {
            return index !== this.paymentMethods.length - 1
        },
        async setAsDefault(paymentMethod) {
            this.loadings[paymentMethod.id] = true

            const { data } = await axios.put(`/api/default-payment-method`, { payment_method: paymentMethod.id })

            this.customer = data

            this.setFlash({ message: 'Default payment method updated!' })

            this.loadings[paymentMethod.id] = false

            this.$refs[paymentMethod.id][0].close()
        },
        showModal (paymentMethod) {
            this.paymentMethod = paymentMethod
            this.isOpen = true
        },
        closeModal () {
            this.paymentMethod = {}
            this.isOpen = false
        },
        async getCustomer () {
            const { data } = await axios.get('/api/customer')

            this.customer = data
        }
    },
    watch: {
        paymentMethods (paymentMethods) {
            this.loadings = {}

            paymentMethods.forEach(paymentMethod => this.$set(this.loadings, paymentMethod.id, false))
        }
    },
    async mounted () {
        await this.getCustomer()
        await this.getPaymentMethods()

        this.loading = false
    }
}
</script>

<style scoped>

</style>
