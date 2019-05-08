<template>
    <page title="用户管理">
        <el-col>
            <el-form :model="form" ref="form" inline size="small">
                <el-form-item prop="user_id" label="ID">
                    <el-input type="text" v-model="form.user_id" placeholder="请输入用户ID" clearable></el-input>
                </el-form-item>
                <el-form-item prop="name" label="姓名">
                    <el-input type="text" v-model="form.name" placeholder="请输入姓名" clearable></el-input>
                </el-form-item>
                <el-form-item prop="status" label="状态">
                    <el-select v-model="form.status" placeholder="全部" clearable>
                        <el-option label="正常" value="1"></el-option>
                        <el-option label="禁用" value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="search">查询</el-button>
                </el-form-item>
                <el-button type="success" size="small" class="fr" @click="addDialog = true">添加</el-button>
            </el-form>
        </el-col>
        <el-col>
            <el-table :data="list" v-loading="theLoading" stripe>
                <el-table-column prop="user_id" label="ID"></el-table-column>
                <el-table-column prop="account" label="用户名"></el-table-column>
                <el-table-column prop="name" label="姓名"></el-table-column>
                <el-table-column prop="phone" label="手机号"></el-table-column>
                <el-table-column prop="status" label="状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.status == 1">正常</span>
                        <span v-else-if="scope.row.status == 2">禁用</span>
                        <span v-else>未知({{scope.row.status}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="role_name" label="角色"></el-table-column>
                <el-table-column prop="lastuptname" label="上次更新人"></el-table-column>
                <el-table-column prop="lastupttime" label="上次更新时间" min-width="100"></el-table-column>
                <el-table-column prop="lastlogintime" label="上次登陆时间" min-width="100"></el-table-column>
                <el-table-column prop="count_login" label="登陆次数"></el-table-column>
                <el-table-column prop="last_login_ip" label="登陆ip"></el-table-column>
                <el-table-column label="操作" min-width="100">
                    <template slot-scope="scope">
                        <el-button type="text" size="mini">编辑</el-button>
                        <el-button type="text" size="mini">删除</el-button>
                        <el-button type="text" size="mini">解锁</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination
                layout="total, prev, pager, next"
                :page-size="form.pageSize"
                :total="total"
                :current-page.sync="form.page"
                @current-change="getList"
            ></el-pagination>
        </el-col>

        <el-dialog title="添加用户" :visible.sync="addDialog" width="30%" :close-on-click-modal="false">
            <user-add @close="addDialog = false" @addSuccess="addSuccess"></user-add>
        </el-dialog>
    </page>
</template>

<script>
    import UserAdd from './user-add';

    export default {
        name: "user",
        data() {
            return {
                theLoading: false,
                form: {
                    user_id: '',
                    name: '',
                    status: '',
                    pageSize: 10,
                    page: 1,
                },
                addDialog: false,
                list: [],

                total: 0,
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                this.theLoading = true;
                api.get('user/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                    this.theLoading = false;
                })
            },
            addSuccess() {
                this.addDialog = false;
                this.form.page = 1;
                this.getList();
            }
        },
        created() {
            this.getList();
        },
        components: {
            UserAdd,
        }
    }
</script>

<style scoped>

</style>
