<template>
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl">Update Plan</h3>
            <button class="btn btn-link font-normal" v-if="canCancel" @click="open">Cancel Plan</button>
        </div>
        <plans-list/>
        <cancel-plan v-if="isOpen" @close="close"/>
    </div>
</template>

<script>
import { mapState } from "vuex"
import PlansList from "./PlansList"
import CancelPlan from "./CancelPlan"

export default {
    name: "Plan",
    components: { PlansList, CancelPlan },
    data () {
        return {
            isOpen: false
        }
    },
    computed: {
        ...mapState(['user']),
        canCancel () {
            return this.user.subscription
                && this.user.subscription.stripe_status === 'active'
                && !this.user.subscription.on_grace_period
        }
    },
    methods: {
        open () {
            this.isOpen = true
        },
        close () {
            this.isOpen = false
        }
    }
}
</script>

<style scoped>

</style>
