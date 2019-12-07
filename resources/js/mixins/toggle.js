export default {
    data () {
        return {
            isOpen: false
        }
    },
    created () {
        const handleEscape = event => {
            if (event.key === 'Escape') {
                this.isOpen = false
            }
        }

        document.addEventListener('keydown', handleEscape)

        this.$once('hook:beforeDestroy', () => document.removeEventListener('keydown', handleEscape))
    },
    methods: {
        toggle () {
            this.isOpen = !this.isOpen
        },
        close () {
            this.isOpen = false
        },
    }
}
