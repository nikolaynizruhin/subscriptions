<template>
    <div>
        <form @submit.prevent="register" class="p-6">
            <div class="mb-6">
                <label for="name" class="block mb-2">Name</label>
                <input v-model="form.name" type="text" name="name" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('name') }" id="name" autofocus required>
                <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('name')" v-text="form.errors.first('name')"/>
            </div>
            <div class="mb-6">
                <label for="email" class="block mb-2">Email</label>
                <input v-model="form.email" type="email" name="email" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('email') }" id="email" required>
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
                {{ form.processing ? 'Loading...' : 'Register' }}
            </button>
        </form>
        <div class="bg-gray-100 p-4 text-center text-sm">
            Already have an account?
            <router-link class="text-blue-500" :to="{ name: 'login' }">
                Login
            </router-link>
        </div>
    </div>
</template>

<script>
import { mapMutations } from 'vuex'
import Form from 'form-backend-validation'

export default {
    name: "Register",
    data () {
        return {
            form: new Form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            })
        }
    },
    methods: {
        ...mapMutations(['login']),
        async register () {
            const data = await this.form.post('/api/register')

            this.login(data)

            this.$router.push({ name: 'dashboard' })
        }
    }
}
</script>
