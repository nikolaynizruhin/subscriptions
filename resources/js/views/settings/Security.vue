<template>
    <div class="w-full card">
        <form class="w-full max-w-3xl p-6">
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block mb-1 md:mb-0 pr-4" for="current_password">Current Password</label>
                </div>
                <div class="md:w-2/3">
                    <input class="form-input w-full" id="current_password" type="password">
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block mb-1 md:mb-0 pr-4" for="new_password">New Password</label>
                </div>
                <div class="md:w-2/3">
                    <input class="form-input w-full" id="new_password" type="password">
                </div>
            </div>
            <div class="md:flex md:items-center">
                <div class="md:w-1/3">
                    <label class="block mb-1 md:mb-0 pr-4" for="confirm_password">Confirm Password</label>
                </div>
                <div class="md:w-2/3">
                    <input class="form-input w-full" id="confirm_password" type="password">
                </div>
            </div>
        </form>
        <div class="bg-gray-100 px-6 py-4">
            <button type="submit" class="btn btn-primary w-full sm:w-auto">
                Update
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Security"
}
</script>

<style scoped>

</style>
