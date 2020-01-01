<template>
    <div>
        <trial-alert v-if="user.on_trial"/>
        <grace-period-alert v-if="onGracePeriod"/>
        <plan class="mb-6"/>
        <cards/>
        <invoices/>
    </div>
</template>

<script>
import Cards from "../../components/cards/Cards"
import Plan from "../../components/plans/Plan"
import TrialAlert from "../../components/alerts/TrialAlert"
import GracePeriodAlert from "../../components/alerts/GracePeriodAlert"
import { mapState } from "vuex"
import Invoices from "../../components/Invoices"

export default {
    name: "Subscription",
    components: { Invoices, TrialAlert, GracePeriodAlert, Plan, Cards },
    computed: {
        ...mapState(['user']),
        onGracePeriod () {
            return this.user.subscription && this.user.subscription.on_grace_period
        }
    }
}
</script>
