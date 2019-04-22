<template>
    <el-container class="container">
        <el-header class="header">
            <el-container class="header-container">
                <el-aside class="header-aside">
                    <div class="header-logo fl">
                        <div>后台管理系统</div>
                    </div>
                </el-aside>
            </el-container>
        </el-header>
        <el-container>
            <el-main>
                <router-view></router-view>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    export default {
        name: "home"
    }
</script>

<style scoped>
    .container {
        height: 100%;
    }

    .header {
        padding: 0;
        border-bottom: solid 1px #e6e6e6;
    }

    .header-container {
        height: 100%;
    }

    .header-aside {
        width: 200px !important;
        height: 100%;
    }

    .header-logo {
        cursor: pointer;
        position: relative;
        height: 100%;
        width: 100%;
        text-align: center; /* 水平居中 */
        display: flex;  /* 垂直居中[这三条] */
        justify-content:center;
        align-items:Center;
    }
</style>
