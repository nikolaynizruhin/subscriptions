<template>
    <div class="flex flex-col items-center min-h-screen px-2 sm:px-0">
        <div class="flex items-center py-12 sm:pt-20">
            <icon name="layers" width="32" height="32"/>
            <h3 class="ml-3 text-2xl tracking-wide">{{ title }}</h3>
        </div>
        <div class="w-full max-w-sm card">
            <form @submit.prevent="register" class="p-6">
                <div class="mb-6">
                    <label for="name" class="block mb-2">Name</label>
                    <input v-model="form.name" type="text" name="name" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('name') }" id="name" autofocus required>
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('name')" v-text="form.errors.first('name')"></p>
                </div>
                <div class="mb-6">
                    <label for="email" class="block mb-2">Email</label>
                    <input v-model="form.email" type="email" name="email" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('email') }" id="email" required>
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('email')" v-text="form.errors.first('email')"></p>
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2">Password</label>
                    <input v-model="form.password" type="password" name="password" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('password') }" id="password" minlength="8" required>
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('password')" v-text="form.errors.first('password')"></p>
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2">Confirm Password</label>
                    <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-input w-full" id="password_confirmation" minlength="8" required>
                </div>
                <button type="submit" class="btn btn-primary w-full sm:w-auto" :disabled="form.processing">
                    <span v-if="form.processing" class="spinner"></span>
                    {{ form.processing ? 'Loading...' : 'Register' }}
                </button>
            </form>
            <div class="bg-gray-100 p-4 text-center text-sm">
                Already have an account?
                <router-link class="text-blue-500 underline" :to="{ name: 'login' }">
                    Login
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import Form from 'form-backend-validation'
import Icon from "../../components/Icon";

export default {
    name: "Register",
    components: { Icon },
    data () {
        return {
            title: app.name,
            form: new Form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            })
        }
    },
    methods: {
        async register () {
            const { token } = await this.form.post('/api/register')

            localStorage.token = token

            this.$router.push({ name: 'dashboard' })
        }
    }
}
</script>
