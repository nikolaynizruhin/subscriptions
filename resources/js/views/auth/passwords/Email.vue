<template>
    <div class="flex flex-col items-center min-h-screen px-2 sm:px-0">
        <div class="flex items-center py-12 sm:pt-20">
            <icon name="layers" width="32" height="32"/>
            <h3 class="ml-3 text-2xl tracking-wide">{{ title }}</h3>
        </div>
        <div class="w-full max-w-sm card">
            <form @submit.prevent="send" class="p-6">
                <div class="mb-6">
                    <label for="email" class="block mb-2">Email</label>
                    <input v-model="form.email" type="email" name="email" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('email') }" id="email" autofocus required>
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('email')" v-text="form.errors.first('email')"></p>
                </div>
                <button type="submit" class="btn btn-primary w-full sm:w-auto" :disabled="form.processing">
                    <span v-if="form.processing" class="spinner"></span>
                    {{ form.processing ? 'Loading...' : 'Send Password Reset Link' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import { mapMutations } from 'vuex'
import Form from 'form-backend-validation'
import Icon from "../../../components/Icon"

export default {
    name: "Email",
    components: { Icon },
    data () {
        return {
            title: app.name,
            form: new Form({ email: '' })
        }
    },
    methods: {
        ...mapMutations(['setFlash']),
        async send () {
            const { status } = await this.form.post('/api/password/email')

            this.setFlash({ message: status })
        }
    }
}
</script>
