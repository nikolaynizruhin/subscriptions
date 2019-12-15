<template>
    <alert>
        <template #title>
            Verify Your Email Address
        </template>
        <template #body>
            Before proceeding, please check your email for a verification link. If you did not receive the email,
            <span class="text-blue-500 cursor-pointer" @click="resend">click here to request another</span>.
        </template>
    </alert>
</template>

<script>
import { mapMutations } from 'vuex'
import Alert from "./Alert";

export default {
    name: "VerifyAlert",
    components: { Alert },
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
