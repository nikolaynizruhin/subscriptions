<template>
    <form @submit.prevent="send" class="p-6">
        <div class="mb-6">
            <label for="email" class="block mb-2">Email</label>
            <input v-model="form.email" type="email" name="email" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('email') }" id="email" autofocus required>
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('email')" v-text="form.errors.first('email')"/>
        </div>
        <button type="submit" class="btn btn-primary w-full sm:w-auto" :disabled="form.processing">
            <span v-if="form.processing" class="spinner"/>
            {{ form.processing ? 'Loading...' : 'Send Password Reset Link' }}
        </button>
    </form>
</template>

<script>
import { mapMutations } from 'vuex'
import Form from 'form-backend-validation'

export default {
    name: "Email",
    data () {
        return {
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
