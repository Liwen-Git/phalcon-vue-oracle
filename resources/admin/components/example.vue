<template>
    <page title="example" :breadcrumbs="{首页: '/login'}">
        <div>hello vue</div>
        <el-button @click="goTest">点击</el-button>
    </page>
</template>

<script>
    export default {
        name: "example",
        methods: {
            goTest() {
                router.push('/login');
            }
        }
    }
</script>

<style scoped>

</style>
