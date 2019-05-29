<template>
    <page title="会计科目">
        <el-col>
            <el-form :model="form" ref="form" size="small" inline>
                <el-form-item prop="subjectCode" label="科目代码">
                    <el-input type="text" v-model="form.subjectCode" placeholder="请输入科目代码" clearable></el-input>
                </el-form-item>
                <el-form-item prop="subjectName" label="科目名称">
                    <el-input type="text" v-model="form.subjectName" placeholder="请输入科目名称" clearable></el-input>
                </el-form-item>
                <el-form-item prop="subjectLevel" label="科目级别">
                    <el-select v-model="form.subjectLevel" placeholder="全部" clearable>
                        <el-option v-for="(item,key) in subjectLevelArr" :key="key" :label="item" :value="key"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item prop="subjectType" label="科目类型">
                    <el-select v-model="form.subjectType" placeholder="全部" clearable>
                        <el-option v-for="(item,key) in subjectTypeArr" :key="key" :label="item" :value="key"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="search">查询</el-button>
                </el-form-item>
                <el-button class="fr" type="success" size="small" @click="addDialog = true">添加</el-button>
            </el-form>
        </el-col>
        <el-col>
            <el-table :data="list" stripe border>
                <el-table-column prop="subject_code" label="科目代码"></el-table-column>
                <el-table-column prop="subject_name" label="科目名称"></el-table-column>
                <el-table-column prop="subject_level" label="科目级别">
                    <template slot-scope="scope">
                        {{subjectLevelArr[scope.row.subject_level]}}
                    </template>
                </el-table-column>
                <el-table-column prop="subject_type" label="科目类型">
                    <template slot-scope="scope">
                        {{subjectTypeArr[scope.row.subject_type]}}
                    </template>
                </el-table-column>
                <el-table-column prop="parent_subject" label="父级科目代码"></el-table-column>
                <el-table-column prop="parent_subject_name" label="父级科目名称"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" size="small" @click="editSubject(scope.row)">编辑</el-button>
                        <el-button type="text" size="small" @click="delSubject(scope.row.subject_code)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination
                    layout="sizes, total, prev, pager, next"
                    :page-size="form.pageSize"
                    :total="total"
                    :current-page.sync="form.page"
                    @current-change="getList"
                    :page-sizes="[10, 50, 100, 200]"
                    @size-change="changePageSize"
            ></el-pagination>
        </el-col>

        <el-dialog title="添加科目" :visible.sync="addDialog" width="40%" :close-on-click-modal="false">
            <subject-add :subjectLevelArr="subjectLevelArr" :subjectTypeArr="subjectTypeArr" @close="addDialog = false" @addSuccess="theSuccess"></subject-add>
        </el-dialog>
        <el-dialog title="编辑科目" :visible.sync="editDialog" width="40%" :close-on-click-modal="false">
            <subject-edit :subjectLevelArr="subjectLevelArr" :subjectTypeArr="subjectTypeArr" :theSubject="theSubject" @close="editDialog = false" @editSuccess="theSuccess"></subject-edit>
        </el-dialog>
    </page>
</template>

<script>
    import SubjectAdd from './add';
    import SubjectEdit from './edit';

    export default {
        name: "accounting-subject-list",
        data() {
            return {
                form: {
                    subjectCode: '',
                    subjectName: '',
                    subjectLevel: '',
                    subjectType: '',

                    page: 1,
                    pageSize: 10,
                },
                total: 0,

                subjectLevelArr: [],
                subjectTypeArr: [],

                list: [],

                addDialog: false,
                editDialog: false,
                theSubject: null,
            }
        },
        methods: {
            getList() {
                api.get('subject/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            search() {
                this.form.page = 1;
                this.getList();
            },
            editSubject(row) {
                this.theSubject = row;
                this.editDialog = true;
            },
            delSubject(code) {
                this.$confirm('确定删除吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    api.post('subject/delete', {subjectCode: code}).then(() => {
                        this.$message.success('删除会计科目成功');
                        this.getList();
                    })
                }).catch(() => {})
            },
            theSuccess() {
                this.form.page = 1;
                this.getList();
            },
            changePageSize(pageSize) {
                this.form.page = 1;
                this.form.pageSize = pageSize;
                this.getList();
            },
            initThePage() {
                api.get('subject/get_subject_levels').then(data => {
                    this.subjectLevelArr = data;
                }).then(() => {
                    api.get('subject/get_subject_types').then(data => {
                        this.subjectTypeArr = data;
                        // todo win上同时执行两个请求会报错，部署到服务器之后再进行尝试
                        this.getList();
                    });
                });
            },
        },
        created() {
            this.initThePage();
        },
        components: {
            SubjectAdd,
            SubjectEdit,
        }
    }
</script>

<style scoped>

</style>
