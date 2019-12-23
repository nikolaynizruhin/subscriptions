<template>
    <alert>
        <template #title>
            You Are On A Grace Period
        </template>
        <template #body>
            Your grace period ends on <strong>{{ user.subscription.ends_at | date }}</strong>.
        </template>
    </alert>
</template>

<script>
import { mapState } from 'vuex'
import Alert from "./Alert"

export default {
    name: "GracePeriodAlert",
    components: { Alert },
    computed: mapState(['user']),
}
</script>
