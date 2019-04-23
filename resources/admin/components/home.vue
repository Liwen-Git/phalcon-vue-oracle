<template>
    <el-container class="container">
        <el-header class="header">
            <el-container class="header-container">
                <el-aside class="header-aside">
                    <div class="header-logo fl">
                        <div class="logo-font">{{logo}}</div>
                    </div>
                </el-aside>
                <el-main>
                    <el-dropdown class="fr" @command="handleCommand">
                        <span class="el-dropdown-link">
                            {{userName}} <i class="el-icon-arrow-down el-icon--right"></i>
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="logout">退出</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </el-main>
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
        name: "home",
        data() {
            return {
                logo: '后台管理系统',
                userName: 'userName',
            }
        },
        methods: {
            handleCommand(command) {

            }
        }
    }
</script>

<style scoped>
    .container {
        height: 100%;
    }

    .header {
        padding: 0;
        border-bottom: solid 1px #e6e6e6;
        background-color: #409EFF;
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

    .logo-font {
        font-size: large;
        font-weight: bold;
    }

    .el-dropdown-link {
        cursor: pointer;
        color: #e6e6e6;
    }

    .el-icon-arrow-down {
        font-size: 12px;
    }
</style>
