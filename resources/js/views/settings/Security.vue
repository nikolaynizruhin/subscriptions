<template>
    <div>
        <h3 class="text-xl mb-4">Update Password</h3>
        <div class="w-full card">
            <form @submit.prevent="update">
                <div class="md:flex md:items-center mb-6 w-full max-w-3xl px-6 pt-6">
                    <div class="md:w-1/3">
                        <label class="block mb-1 md:mb-0 pr-4" for="password">Current Password</label>
                    </div>
                    <div class="md:w-2/3">
                        <input v-model="form.password" name="password" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('password') }" id="password" type="password" required>
                        <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('password')" v-text="form.errors.first('password')"/>
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6 w-full max-w-3xl px-6">
                    <div class="md:w-1/3">
                        <label class="block mb-1 md:mb-0 pr-4" for="new_password">New Password</label>
                    </div>
                    <div class="md:w-2/3">
                        <input v-model="form.new_password" name="new_password" class="form-input w-full" :class="{ 'is-invalid': form.errors.has('new_password') }" id="new_password" type="password" minlength="8" required>
                        <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('new_password')" v-text="form.errors.first('new_password')"/>
                    </div>
                </div>
                <div class="md:flex md:items-center w-full max-w-3xl px-6 pb-6">
                    <div class="md:w-1/3">
                        <label class="block mb-1 md:mb-0 pr-4" for="confirm_password">Confirm Password</label>
                    </div>
                    <div class="md:w-2/3">
                        <input v-model="form.new_password_confirmation" name="new_password_confirmation" class="form-input w-full" id="confirm_password" type="password" minlength="8" required>
                    </div>
                </div>
                <div class="bg-gray-100 px-6 py-4">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto" :disabled="form.processing">
                        <span v-if="form.processing" class="spinner"/>
                        {{ form.processing ? 'Loading...' : 'Update' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapMutations } from "vuex"
import Form from 'form-backend-validation'

export default {
    name: "Security",
    data () {
        return {
            form: new Form({
                password: '',
                new_password: '',
                new_password_confirmation: '',
            })
        }
    },
    methods: {
        ...mapMutations(['setFlash', 'login']),
        async update () {
            const data = await this.form.put('/api/password')

            this.login(data)

            this.setFlash({ message: 'Password updated successfully!' })
        }
    }
}
</script>
