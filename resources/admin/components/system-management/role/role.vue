<template>
    <page title="角色管理">
        <el-col>
            <el-form :model="form" ref="form" inline size="small">
                <el-form-item prop="role_id" label="ID">
                    <el-input type="text" v-model="form.role_id" placeholder="请输入角色ID" clearable></el-input>
                </el-form-item>
                <el-form-item prop="role_name" label="角色名">
                    <el-input type="text" v-model="form.role_name" placeholder="请输入角色名" clearable></el-input>
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
                <el-table-column prop="role_id" label="ID"></el-table-column>
                <el-table-column prop="role_name" label="角色名"></el-table-column>
                <el-table-column prop="status" label="状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.status == 1">正常</span>
                        <span v-else-if="scope.row.status == 2">禁用</span>
                        <span v-else>未知({{scope.row.status}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="lastuptname" label="上次更新人"></el-table-column>
                <el-table-column prop="lastupttime" label="上次更新时间" min-width="100"></el-table-column>
                <el-table-column label="操作" min-width="100">
                    <template slot-scope="scope">
                        <el-button type="text" size="mini" @click="edit(scope.row)">编辑</el-button>
                        <el-button type="text" size="mini" @click="deleteRole(scope.row.user_id)">删除</el-button>
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

        <el-dialog title="添加角色" :visible.sync="addDialog" width="30%" :close-on-click-modal="false">
            <role-add @close="addDialog = false" @addSuccess="addSuccess"></role-add>
        </el-dialog>

        <el-dialog title="编辑角色" :visible.sync="editDialog" width="30%" :close-on-click-modal="false">

        </el-dialog>
    </page>
</template>

<script>
    import RoleAdd from './role-add';

    export default {
        name: "role",
        data() {
            return {
                theLoading: false,
                form: {
                    role_id: '',
                    role_name: '',
                    status: '',

                    page: 1,
                    pageSize: 10,
                },
                total: 0,
                list: [],
                addDialog: false,
                editDialog: false,
            }
        },
        methods: {
            getList() {
                api.get('role/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            search() {
                this.form.page = 1;
                this.getList();
            },
            edit() {

            },
            deleteRole() {

            },
            addSuccess() {

            }
        },
        created() {
            this.getList();
        },
        components: {
            RoleAdd,
        }
    }
</script>

<style scoped>

</style>
