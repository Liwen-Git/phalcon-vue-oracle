<template>
    <el-container class="container">
        <!-- 页面头部 -->
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
        <!-- 下部分 -->
        <el-container>
            <!-- 左部分 菜单列表 -->
            <left-menu :menus="menus"></left-menu>
            <!-- 主页面 内容部分 -->
            <el-main class="main-content">
                <el-col :span="24">
                    <transition name="fade" mode="out-in" appear>
                        <router-view v-loading="globalLoading"></router-view>
                    </transition>
                </el-col>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    import LeftMenu from '../../assets/components/left_menu';
    import {mapState} from 'vuex';

    export default {
        name: "home",
        data() {
            return {
                logo: '后台管理系统',
                userName: 'userName',
                menus: [
                    {
                        name: 'The 1',
                        url: '/',
                        sub: [
                            {
                                name: 'The 1-1',
                                url: '/login',
                            },
                            {
                                name: 'The 1-2',
                                url: '',
                            }
                        ]
                    },
                    {
                        name: 'The 2',
                        url: '/login',
                    }
                ],
            }
        },
        methods: {
            handleCommand(command) {

            }
        },
        created() {
            // console.log(this.$store.state.globalLoading);
        },
        computed: {
            ...mapState([
                'globalLoading',
            ]),
        },
        components: {
            LeftMenu,
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

    .main-content {
        overflow-y: scroll;
    }
</style>
