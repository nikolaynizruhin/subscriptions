<template>
    <div class="flex flex-col min-h-screen">
        <header class="flex">
            <div class="bg-gray-800 w-64 px-4 py-3 flex items-center">
                <img src="/images/logo.svg" class="h-8 w-10" alt="Logo">
                <h3 class="ml-3 text-white text-xl tracking-wide">Invoice</h3>
            </div>
            <div class="bg-white border-b flex-grow px-4 py-3 shadow flex justify-end">
                <div class="relative">
                    <button class="flex items-center focus:outline-none" @click="isOpen = !isOpen">
                        <img src="https://github.com/adamwathan.png" alt="Adam Wathan's avatar" class="rounded-full h-8 w-8">
                        <span class="ml-2">Adam Wathan</span>
                        <svg class="ml-1 h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"/>
                        </svg>
                    </button>
                    <div v-if="isOpen" class="absolute right-0 left-auto bg-white border shadow py-2 rounded">
                        <a href="#" @click.prevent="logout" class="block px-6 py-1 hover:bg-gray-300">Logout</a>
                    </div>
                </div>
            </div>
        </header>
        <div class="flex flex-grow">
            <aside class="gradient w-64 p-4">
                <ul class="text-white">
                    <li class="p-2">Invoices</li>
                    <li class="p-2">Projects</li>
                    <li class="p-2">Users</li>
                </ul>
            </aside>
            <main class="flex-grow p-4">
                Content
            </main>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Index",
        data () {
            return {
                isOpen: false
            }
        },
        methods: {
            logout () {
                localStorage.removeItem('token')
                this.$router.push('/login')
            }
        }
    }
</script>
