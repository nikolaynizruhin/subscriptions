<template>
    <alert>
        <template #title>
            You Are On A Trial Period
        </template>
        <template #body>
            Your trial period ends on <strong>{{ user.trial_ends_at | date }}</strong>.
        </template>
    </alert>
</template>

<script>
import { mapState } from 'vuex'
import Alert from "./Alert"

export default {
    name: "TrialAlert",
    components: { Alert },
    computed: mapState(['user']),
}
</script>
