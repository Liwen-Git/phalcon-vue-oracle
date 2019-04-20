<template>
    <div>
        <div>hello vue</div>
        <el-button @click="goTest">点击</el-button>
    </div>
</template>

<script>
    export default {
        name: "example",
        methods: {
            goTest() {
                router.push('/test');
            }
        }
    }
</script>

<style scoped>

</style>
