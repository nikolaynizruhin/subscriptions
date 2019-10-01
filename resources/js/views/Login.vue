<template>
    <div class="flex flex-col items-center min-h-screen px-2 sm:px-0">
        <div class="flex items-center py-12 md:pt-24">
            <img src="/images/logo.svg" class="h-10" alt="Logo">
            <h3 class="ml-3 text-2xl tracking-wide">Invoice</h3>
        </div>
        <div class="w-full max-w-sm card">
            <form @submit.prevent="login">
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
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';

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
            login () {
                this.form
                    .post('/api/login')
                    .then(({ token }) => {
                        localStorage.setItem('token', token)
                        this.$router.push(this.$route.query.redirect || '/invoices')
                    })
            }
        }
    }
</script>
