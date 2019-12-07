<template>
    <form @submit.prevent="reset" class="p-6">
        <div class="mb-6">
            <label for="email" class="block mb-2">Email</label>
            <input v-model="form.email" type="email" name="email" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('email') }" id="email" autofocus required>
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('email')" v-text="form.errors.first('email')"/>
        </div>
        <div class="mb-6">
            <label for="password" class="block mb-2">Password</label>
            <input v-model="form.password" type="password" name="password" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('password') }" id="password" minlength="8" required>
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('password')" v-text="form.errors.first('password')"/>
        </div>
        <div class="mb-6">
            <label for="password" class="block mb-2">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-input w-full" id="password_confirmation" minlength="8" required>
        </div>
        <button type="submit" class="btn btn-primary w-full sm:w-auto" :disabled="form.processing">
            <span v-if="form.processing" class="spinner"/>
            {{ form.processing ? 'Loading...' : 'Reset Password' }}
        </button>
    </form>
</template>

<script>
import { mapMutations } from 'vuex'
import Form from 'form-backend-validation'

export default {
    name: "Reset",
    data () {
        return {
            form: new Form({
                token: this.$route.params.token,
                email: this.$route.query.email || '',
                password: '',
                password_confirmation: '',
            })
        }
    },
    methods: {
        ...mapMutations(['setFlash', 'login']),
        async reset () {
            const { token, status, user } = await this.form.post('/api/password/reset')

            this.login({ token, user })

            this.setFlash({ message: status })

            this.$router.push({ name: 'dashboard' })
        }
    }
}
</script>
