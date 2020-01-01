<template>
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl">Cards</h3>
            <button class="btn btn-primary" @click="open">Add Card</button>
        </div>
        <cards-list/>
        <add-card v-if="isOpen" @close="close"/>
    </div>
</template>

<script>
import CardsList from "./CardsList"
import AddCard from "./AddCard"

export default {
    name: "Cards",
    components: { AddCard, CardsList },
    data () {
        return {
            isOpen: false
        }
    },
    methods: {
        open () {
            this.isOpen = true
        },
        close () {
            this.isOpen = false
        }
    }
}
</script>

<style scoped>

</style>
