<template>
    <div class="flex flex-col items-center min-h-screen px-2 sm:px-0">
        <div class="flex items-center py-12 sm:pt-20">
            <icon name="layers" width="32" height="32"/>
            <h3 class="ml-3 text-2xl tracking-wide">{{ title }}</h3>
        </div>
        <div class="w-full max-w-sm card">
            <form @submit.prevent="confirm" class="p-6">
                <div class="mb-6">
                    <label for="password" class="block mb-2">Password</label>
                    <input v-model="form.password" type="password" name="password" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('password') }" id="password" required>
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('password')" v-text="form.errors.first('password')"></p>
                </div>
                <div class="flex flex-col sm:flex-row items-center">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto mb-4 sm:mb-0" :disabled="form.processing">
                        <span v-if="form.processing" class="spinner"></span>
                        {{ form.processing ? 'Loading...' : 'Confirm Password' }}
                    </button>
                    <router-link :to="{ name: 'password.request' }" class="sm:ml-auto text-blue-500">
                        Forgot Your Password?
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapMutations } from 'vuex'
import Form from 'form-backend-validation'
import Icon from "../../../components/Icon";

export default {
    name: "Login",
    components: { Icon },
    data () {
        return {
            title: app.name,
            form: new Form({ password: '' })
        }
    },
    methods: {
        ...mapMutations(['setUser']),
        async confirm () {
            const { confirmed, user } = await this.form.post('/api/password/confirm')

            confirmed && this.setUser(user)

            this.$router.push(this.$route.query.redirect || '/')
        }
    }
}
</script>
