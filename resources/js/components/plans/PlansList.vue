<template>
    <div class="w-full card">
        <div v-if="loading" class="flex items-center justify-center p-6">
            <span class="spinner text-gray-500 w-6 h-6"/>
        </div>
        <div v-else-if="!plans.length" class="p-6 flex flex-col items-center">
            <icon name="package" class="text-gray-500 mb-2" stroke-width="1" width="56" height="56"/>
            <p class="text-gray-500">No subscription plans.</p>
        </div>
        <form v-else @submit.prevent="update">
            <div class="block p-6">
                <div v-for="plan in plans">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" name="plan" :value="plan.id" v-model="form.plan" :disabled="onGracePeriod" required>
                        <span class="ml-2" :class="{ 'text-red-500': form.errors.has('plan') }">
                            {{ plan.nickname }} ({{ plan.amount | money }} / {{ plan.interval }})
                        </span>
                    </label>
                </div>
                <p class="text-red-500 text-sm ml-6 mt-1" v-if="form.errors.has('plan')" v-text="form.errors.first('plan')"/>
            </div>
            <div class="bg-gray-100 px-6 py-4">
                <button type="submit" class="btn btn-primary w-full sm:w-auto" :disabled="form.processing">
                    <span v-if="form.processing">
                        <span class="spinner"/>
                        Loading...
                    </span>
                    <span v-else-if="onGracePeriod">Resume</span>
                    <span v-else-if="hasNoSubscription">Subscribe</span>
                    <span v-else>Update</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import Form from "form-backend-validation";
import { mapMutations, mapState } from "vuex"

export default {
    name: "PlansList",
    data () {
        return {
            form: new Form({
                plan: ''
            }, {
                resetOnSuccess: false
            }),
            plans: [],
            loading: true
        }
    },
    computed: {
        ...mapState(['user']),
        onGracePeriod () {
            return this.user.subscription && this.user.subscription.on_grace_period
        },
        hasNoSubscription () {
            return !this.user.subscription
        },
        method () {
            return (this.onGracePeriod || this.hasNoSubscription) ? 'post' : 'put'
        },
        prefix () {
            return this.onGracePeriod ? 'resume-' : ''
        }
    },
    methods: {
        ...mapMutations(['setFlash', 'setUser']),
        async update () {
            const user = await this.form[this.method](`/api/${this.prefix}subscription`)

            this.setUser(user)

            this.form.plan = this.user.subscription.stripe_plan

            this.setFlash({ message: 'Subscription updated successfully!' })
        },
        async getPlans () {
            const { data } = await axios.get('/api/plans')

            this.plans = data
        }
    },
    async mounted () {
        await this.getPlans()

        if (this.user.subscription) {
            this.form.plan = this.user.subscription.stripe_plan
        }

        this.loading = false
    }
}
</script>

<style scoped>

</style>
