<template>
    <div class="welcome_bg"></div>
</template>

<script>
    export default {
        name: "welcome",
        methods: {

        },
        created() {

        }
    }
</script>

<style scoped>
    .welcome_bg {
        width: 100%;
        height: 100%;
        position: fixed;
        background-image: url(../../assets/images/welcome-bg.png);
        background-repeat: no-repeat;
        background-size: cover;
        overflow: hidden;
    }
</style>
