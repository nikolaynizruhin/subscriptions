<template>
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl">Cards</h3>
            <button class="btn btn-primary" @click="showModal">Add Card</button>
        </div>
        <cards/>
        <add-card v-if="isOpen" @close="closeModal"/>
    </div>
</template>

<script>
import Cards from "../../components/Cards"
import AddCard from "../../components/AddCard"

export default {
    name: "Subscription",
    components: { AddCard, Cards },
    data () {
        return {
            isOpen: false
        }
    },
    methods: {
        showModal () {
            this.isOpen = true
        },
        closeModal () {
            this.isOpen = false
        }
    }
}
</script>
