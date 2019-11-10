<template>
    <div dusk="verify-alert" class="card p-4 mb-4" role="alert">
        <div class="flex">
            <div class="py-1 mr-4">
                <icon name="alert-circle" width="24" height="24"/>
            </div>
            <div>
                <p class="font-bold">Verify Your Email Address</p>
                <p class="text-sm">
                    Before proceeding, please check your email for a verification link. If you did not receive the email,
                    <span class="text-blue-500 cursor-pointer" @click="resend">click here to request another</span>.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { mapMutations } from 'vuex'
import Icon from "./Icon"

export default {
    name: "Verify",
    components: { Icon },
    computed: {
        verifyLink () {
            return this.$route.query['verify-link']
        }
    },
    async created () {
        if (!this.verifyLink) {
            return
        }

        const { data } = await axios.get(this.verifyLink)

        if (data.verified) {
            this.setUser(data.user)

            this.setFlash({ message: 'Your email was successfully verified!' })
        }

        this.$router.replace({ 'query': null })
    },
    methods: {
        ...mapMutations(['setFlash', 'setUser']),
        async resend () {
            const { data } = await axios.post('/api/email/resend')

            data.resent && this.setFlash({ message: 'A fresh verification link has been sent to your email address.' })
        }
    }
}
</script>

<style scoped>

</style>
