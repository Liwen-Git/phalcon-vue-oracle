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
                            {{user.account}} <i class="el-icon-arrow-down el-icon--right"></i>
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="logout">退出</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </el-main>
            </el-container>
        </el-header>
        <!-- 下部分 -->
        <el-container class="next-container">
            <!-- 左部分 菜单列表 -->
            <left-menu :menus="menus"></left-menu>
            <!-- 主页面 内容部分 -->
            <el-main class="main-content">
                <el-tabs class="tabs" v-model="activeTabName" @tab-click="tabClick" @tab-remove="tabRemove" type="border-card">
                    <el-tab-pane v-for="item in tabList" :key="item.name" :name="item.path" :label="item.name" :closable="item.closable">

                    </el-tab-pane>
                </el-tabs>
                <el-col :span="24" class="main-col">
                    <transition name="fade" mode="out-in" appear>
                        <keep-alive>
                            <router-view v-loading="globalLoading"></router-view>
                        </keep-alive>
                    </transition>
                </el-col>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    import LeftMenu from '../../assets/components/left_menu';
    import {mapState, mapMutations} from 'vuex';

    export default {
        name: "home",
        data() {
            return {
                logo: '账务系统',
            }
        },
        methods: {
            ...mapMutations('navTabs', [
                'closeTab',
                'addTab',
                'setActiveTabName',
            ]),
            handleCommand(command) {
                switch (command) {
                    case 'logout':
                        this.logout();
                        break;
                }
            },
            logout() {
                this.$confirm('确认退出吗？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(() => {
                    api.post('/self/logout').then(() => {
                        this.$message.success('退出成功');
                    });
                    this.$store.dispatch('clearUserAndMenus');
                    this.$router.replace('/login');
                }).catch(() => {

                })
            },
            tabClick() {
                this.$router.push(this.activeTabName);
            },
            tabRemove(route) {
                this.closeTab(route);
                this.$router.push(this.activeTabName);
            }
        },
        created() {
            // 登陆验证
            if(!this.user){
                this.$message.warning('您尚未登录');
                this.$router.replace('/login');
                return ;
            }
        },
        mounted() {
            // 刷新时，以当前路由作为tab加入tabList
            if (this.$route.path !== '/welcome') {
                this.addTab({path: this.$route.path, name: this.$route.name});
                this.setActiveTabName(this.$route.path);
            } else {
                // this.$router.push('/welcome');
            }
        },
        computed: {
            ...mapState([
                'globalLoading',
                'user',
                'menus',
                'rules',
            ]),
            activeTabName: {
                get() {
                    return this.$store.state.navTabs.activeTabName;
                },
                set(value) {
                    return this.$store.commit("navTabs/setActiveTabName", value);
                }
            },
            ...mapState('navTabs', [
                'tabList',
            ]),
        },
        components: {
            LeftMenu,
        },
        watch: {
            '$route'(to) {
                let flag = false;
                for (let tab of this.tabList) {
                    if (tab.name === to.name) {
                        flag = true;
                        this.setActiveTabName(to.path);
                    }
                }
                if (!flag) {
                    this.addTab({path: to.path, name: to.name});
                    this.setActiveTabName(to.path);
                }
            }
        }
    }
</script>

<style scoped>
    .container {
        height: 100%;
    }

    .next-container {
        overflow: hidden;
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
        overflow: auto;
        padding: 0;
    }

    .main-col {
        height: 100%;
        margin-top: -30px;
    }

    .tabs {
        border: none;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

</style>
