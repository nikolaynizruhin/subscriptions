<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <g v-if="name === 'chevron-down'"><polyline points="6 9 12 15 18 9"></polyline></g>
        <g v-else-if="name === 'layers'"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></g>
        <g v-else-if="name === 'log-out'"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></g>
        <g v-else-if="name === 'menu'"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></g>
        <g v-else-if="name === 'pie-chart'"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></g>
        <g v-else-if="name === 'x'"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></g>
    </svg>
</template>

<script>
export default {
    name: "Icon",
    props: {
        name: String,
    }
}
</script>
