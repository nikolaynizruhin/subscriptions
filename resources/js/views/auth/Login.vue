<template>
    <div>
        <form @submit.prevent="login" class="p-6">
            <div class="mb-6">
                <label for="email" class="block mb-2">Email</label>
                <input v-model="form.email" type="email" name="email" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('email') }" id="email" autofocus required>
                <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('email')" v-text="form.errors.first('email')"></p>
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-2">Password</label>
                <input v-model="form.password" type="password" name="password" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('password') }" id="password" required>
                <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('password')" v-text="form.errors.first('password')"></p>
            </div>
            <div class="flex flex-col sm:flex-row items-center">
                <button type="submit" class="btn btn-primary w-full sm:w-auto mb-4 sm:mb-0" :disabled="form.processing">
                    <span v-if="form.processing" class="spinner"></span>
                    {{ form.processing ? 'Loading...' : 'Login' }}
                </button>
                <router-link :to="{ name: 'password.request' }" class="sm:ml-auto text-blue-500">
                    Forgot Your Password?
                </router-link>
            </div>
        </form>
        <div class="bg-gray-100 p-4 text-center text-sm">
            Don't have an account?
            <router-link class="text-blue-500" :to="{ name: 'register' }">
                Register
            </router-link>
        </div>
    </div>
</template>

<script>
import { mapMutations } from 'vuex'
import Form from 'form-backend-validation'

export default {
    name: "Login",
    data () {
        return {
            form: new Form({
                email: '',
                password: '',
            })
        }
    },
    methods: {
        ...mapMutations(['setUser']),
        async login () {
            const { token, user } = await this.form.post('/api/login')

            localStorage.token = token

            this.setUser(user)

            this.$router.push(this.$route.query.redirect || '/')
        }
    }
}
</script>
