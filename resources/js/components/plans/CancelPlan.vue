<template>
    <modal @close="close">
        <div v-if="loadings.main" class="flex items-center justify-center p-6">
            <span class="spinner text-gray-500 w-6 h-6"/>
        </div>
        <div v-else class="max-w-lg w-full">
            <div class="p-6">
                <h3 class="text-xl mb-4">Cancel Plan</h3>
                <p>
                    Are you sure you want to cancel <strong>{{ plan.nickname }}</strong> subscription plan?
                </p>
            </div>
            <div class="bg-gray-100 px-6 py-4 rounded-b">
                <button type="button" @click="cancel" class="btn btn-danger mr-2" :disabled="loadings.button">
                    <span v-if="loadings.button" class="spinner"/>
                    {{ loadings.button ? 'Loading...' : 'Cancel Plan' }}
                </button>
                <button @click="close" class="btn btn-link">
                    Cancel
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import { mapMutations, mapState } from 'vuex'
import Modal from "../Modal"

export default {
    name: "CancelPlan",
    components: { Modal },
    data () {
        return {
            loadings: {
                main: true,
                button: false
            },
            plan: null,
        }
    },
    computed: mapState(['user']),
    methods: {
        ...mapMutations(['setFlash', 'setUser']),
        close () {
            this.$emit('close')
        },
        async cancel () {
            this.loadings.button = true

            const { data } = await axios.delete('/api/subscription')

            this.setUser(data)

            this.setFlash({ message: 'Plan canceled!' })

            this.loadings.button = false

            this.close()
        },
        async getPlan () {
            const { data } = await axios.get(`/api/plans/${this.user.subscription.stripe_plan}`)

            this.plan = data
        }
    },
    async mounted () {
        await this.getPlan()

        this.loadings.main = false
    }
}
</script>

<style scoped>

</style>
