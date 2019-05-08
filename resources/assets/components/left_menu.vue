<template>
    <el-menu
        text-color="#303133"
        active-text-color="#409EFF"
        :default-active="currentMenu"
        @select="change"
        :unique-opened="true"
        class="menu"
        :router="true"
    >
        <template v-for="secMenu in menus">
            <!-- 有子菜单 -->
            <el-submenu v-if="secMenu.sub && secMenu.sub.length > 0" :index="secMenu.menu_name">
                <template slot="title">
                    <span slot="title">{{secMenu.menu_name}}</span>
                </template>
                <el-menu-item v-for="(item,index) in secMenu.sub" :key="index" :index="item.menu_url">{{item.menu_name}}</el-menu-item>
            </el-submenu>
            <!-- 没有子菜单 -->
            <el-menu-item v-if="!secMenu.sub || secMenu.sub.length <= 0" :index="secMenu.menu_url">
                <span slot="title">{{secMenu.menu_name}}</span>
            </el-menu-item>
        </template>
    </el-menu>
</template>

<script>
    export default {
        name: "left_menu",
        props: {
            menus: {
                type: Array,
                default: [],
            }
        },
        data() {
            return {
                currentMenu: '',
            }
        },
        methods: {
            change(index, indexPath) {

            }
        }
    }
</script>

<style scoped>
    .menu:not(.el-menu--collapse) {
        width: 250px;
        min-height: 400px;
    }
</style>
