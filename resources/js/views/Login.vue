<template>
    <div class="flex justify-center items-center h-screen bg-gray-100">
        <div class="w-full max-w-sm mx-2 card">
            <form>
                <div class="mb-6">
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" name="email" class="form-input w-full" id="email">
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2">Password</label>
                    <input type="password" name="password" class="form-input w-full" id="password">
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login"
    }
</script>
