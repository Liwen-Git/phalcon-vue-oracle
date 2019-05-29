<template>
    <page title="业务类型">
        <el-col>
            <el-form :model="form" ref="form" inline size="small">
                <el-form-item prop="business_types" label="业务类型">
                    <el-cascader
                            v-model="form.business_types"
                            placeholder="请选择(可搜索)"
                            :options="businessTypes"
                            :props="{value: 'type', label: 'name', children: 'child'}"
                            filterable
                            change-on-select
                            clearable
                            class="w-300"
                    ></el-cascader>
                </el-form-item>
                <el-form-item prop="state" label="状态">
                    <el-select v-model="form.state" placeholder="全部" clearable>
                        <el-option label="生效" value="1"></el-option>
                        <el-option label="失效" value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="search">搜索</el-button>
                </el-form-item>
                <el-button type="success" size="small" class="fr" @click="addDialog = true">添加</el-button>
            </el-form>
        </el-col>
        <el-col>
            <el-table :data="list" stripe border>
                <el-table-column prop="busi_type" label="业务大类代码"></el-table-column>
                <el-table-column prop="busy_type_name" label="业务大类名称"></el-table-column>
                <el-table-column prop="second_busi_type" label="业务小类代码"></el-table-column>
                <el-table-column prop="second_busi_name" label="业务小类名称"></el-table-column>
                <el-table-column prop="sub_busi_type" label="业务子类代码"></el-table-column>
                <el-table-column prop="sub_busi_name" label="业务子类名称"></el-table-column>
                <el-table-column prop="state" label="状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.state == 1">生效</span>
                        <span v-else-if="scope.row.state == 2">失效</span>
                        <span v-else>未知({{scope.row.state}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="remark" label="备注"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" @click="editBusinessType(scope.row)">编辑</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination
                    layout="sizes, total, prev, pager, next"
                    :page-size="form.page_size"
                    :total="total"
                    :current-page.sync="form.page"
                    @current-change="getList"
                    :page-sizes="[10, 50, 100, 200]"
                    @size-change="changePageSize"
            ></el-pagination>
        </el-col>

        <el-dialog title="新增业务类型" :visible.sync="addDialog" :close-on-click-modal="false" width="25%">
            <business-add @close="addDialog = false" @addSuccess="theSuccess"></business-add>
        </el-dialog>

        <el-dialog title="编辑业务类型" :visible.sync="editDialog" :close-on-click-modal="false" width="25%">
            <business-edit :theBusinessType="theBusinessType" @close="editDialog = false" @editSuccess="theSuccess"></business-edit>
        </el-dialog>
    </page>
</template>

<script>
    import BusinessAdd from './add';
    import BusinessEdit from './edit';

    export default {
        name: "business-type-list",
        data() {
            return {
                form: {
                    business_types: [],
                    state: '',

                    page: 1,
                    page_size: 10,
                },
                total: 0,
                list: [],
                businessTypes: [],

                addDialog: false,
                editDialog: false,
                theBusinessType: null,
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('business_type/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(size) {
                this.form.page_size = size;
                this.form.page = 1;
                this.getList();
            },
            initPage() {
                api.get('business_type/businessTypesOfNew').then(data => {
                    this.businessTypes = data;
                }).then(() => {
                    this.getList();
                })
            },
            theSuccess() {
                this.form.page = 1;
                this.getList();
            },
            editBusinessType(row) {
                this.theBusinessType = row;
                this.editDialog = true;
            }
        },
        created() {
            this.initPage();
        },
        components: {
            BusinessAdd,
            BusinessEdit,
        }
    }
</script>

<style scoped>

</style>
