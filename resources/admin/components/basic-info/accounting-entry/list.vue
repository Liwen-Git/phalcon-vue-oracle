<template>
    <page title="会计分录">
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
                <el-form-item>
                    <el-button type="primary" @click="search">搜索</el-button>
                </el-form-item>
                <el-button type="success" size="small" class="fr" @click="addDialog = true">添加</el-button>
            </el-form>
        </el-col>
        <el-col>
            <el-table :data="list" stripe border>
                <el-table-column prop="busy_type_name" label="业务大类名称"></el-table-column>
                <el-table-column prop="second_busi_name" label="业务小类名称"></el-table-column>
                <el-table-column prop="sub_busi_name" label="业务子类名称"></el-table-column>
                <el-table-column prop="subject_code" label="科目代码"></el-table-column>
                <el-table-column prop="subject_name" label="科目名称"></el-table-column>
                <el-table-column prop="depit_credit_dir" label="借贷方向">
                    <template slot-scope="scope">
                        <span v-if="scope.row.depit_credit_dir == 'D'">借方</span>
                        <span v-else-if="scope.row.depit_credit_dir == 'C'">贷方</span>
                        <span v-else>未知({{scope.row.depit_credit_dir}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="is_real" label="是否实时记账">
                    <template slot-scope="scope">
                        <span v-if="scope.row.is_real == '0'">否</span>
                        <span v-else-if="scope.row.is_real == '1'">是</span>
                        <span v-else>未知({{scope.row.is_real}})</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" @click="editEntry(scope.row)">编辑</el-button>
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

        <el-dialog title="新增会计分录" :visible.sync="addDialog" :close-on-click-modal="false" :show-close="false" width="30%">
            <add-entry :businessTypes="businessTypes" @close="addDialog = false" @addSuccess="theSuccess"></add-entry>
        </el-dialog>

        <el-dialog title="编辑会计分录" :visible.sync="editDialog" :close-on-click-modal="false" :show-close="false" width="30%">
            <edit-entry :theEntry="theEntry" @close="editDialog = false" @editSuccess="theSuccess"></edit-entry>
        </el-dialog>
    </page>
</template>

<script>
    import AddEntry from './add';
    import EditEntry from './edit';

    export default {
        name: "accounting-entry-list",
        data() {
            return {
                form: {
                    business_types: [],

                    page: 1,
                    page_size: 10,
                },
                total: 0,
                list: [],
                businessTypes: [],

                addDialog: false,
                editDialog: false,
                theEntry: null,
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('accounting_entry/list', this.form).then(data => {
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
                api.get('accounting_entry/businessTypesOfNew').then(data => {
                    this.businessTypes = data;
                }).then(() => {
                    this.getList();
                })
            },
            theSuccess() {
                this.form.page = 1;
                this.getList();
            },
            editEntry(row) {
                this.theEntry = row;
                this.editDialog = true;
            }
        },
        created() {
            this.initPage();
        },
        components: {
            AddEntry,
            EditEntry,
        }
    }
</script>

<style scoped>

</style>
