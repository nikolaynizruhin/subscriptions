export default {
    data () {
        return {
            isOpen: false
        }
    },
    created () {
        document.addEventListener('keydown', this.handleEscape)
    },
    beforeDestroy () {
        document.removeEventListener('keydown', this.handleEscape)
    },
    methods: {
        handleEscape (event) {
            if (event.key === 'Escape') {
                this.isOpen = false
            }
        }
    }
}
