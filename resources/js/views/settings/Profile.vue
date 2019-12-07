<template>
    <div>
        <h3 class="text-xl mb-4">Update Account</h3>
        <div class="w-full card">
            <form @submit.prevent="update">
                <div class="md:flex md:items-center mb-6 w-full max-w-3xl px-6 pt-6">
                    <div class="md:w-1/3">
                        <label class="block mb-1 md:mb-0 pr-4" for="name">Name</label>
                    </div>
                    <div class="md:w-2/3">
                        <input v-model="form.name" name="name" class="form-input w-full" id="name" type="text" :class="{ 'is-invalid': form.errors.has('name') }" autofocus required>
                        <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('name')" v-text="form.errors.first('name')"/>
                    </div>
                </div>
                <div class="md:flex md:items-center w-full max-w-3xl px-6 pb-6">
                    <div class="md:w-1/3">
                        <label class="block mb-1 md:mb-0 pr-4" for="email">Email</label>
                    </div>
                    <div class="md:w-2/3">
                        <input v-model="form.email" name="email" class="form-input w-full" id="email" type="email" :class="{ 'is-invalid': form.errors.has('email') }" required>
                        <p class="text-red-500 text-sm mt-1" v-if="form.errors.has('email')" v-text="form.errors.first('email')"/>
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
import { mapMutations, mapState } from "vuex"
import Form from 'form-backend-validation'

export default {
    name: "Profile",
    data () {
        return {
            form: new Form({
                name: '',
                email: '',
            }, {
                resetOnSuccess: false,
            })
        }
    },
    computed: mapState(['user']),
    methods: {
        ...mapMutations(['setUser', 'setFlash']),
        async update () {
            const user = await this.form.put('/api/user')

            this.setUser(user)

            this.setFlash({ message: 'Profile updated successfully!' })
        }
    },
    mounted () {
        this.form.name = this.user.name
        this.form.email = this.user.email
    }
}
</script>

<style scoped>

</style>
