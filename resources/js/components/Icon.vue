<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <use :xlink:href="`/images/feather-sprite.svg#${name}`"/>
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
