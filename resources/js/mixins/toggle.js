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
        toggle () {
            this.isOpen = !this.isOpen
        },
        close () {
            this.isOpen = false
        },
        handleEscape (event) {
            if (event.key === 'Escape') {
                this.isOpen = false
            }
        }
    }
}
