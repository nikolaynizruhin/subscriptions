<template>
    <div>
        <plan class="mb-6"/>
        <cards/>
    </div>
</template>

<script>
import Cards from "../../components/cards/Cards"
import Plan from "../../components/plans/Plan"

export default {
    name: "Subscription",
    components: { Plan, Cards },
}
</script>
