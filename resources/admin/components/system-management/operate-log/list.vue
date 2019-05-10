<template>
    <page title="操作日志">
        <el-col>
            <el-form :model="form" ref="form" size="small" inline>
                <el-form-item prop="userId" label="用户ID">
                    <el-input type="text" v-model="form.userId" placeholder="请输入用户ID"></el-input>
                </el-form-item>
                <el-form-item prop="action" label="操作内容">
                    <el-input type="text" v-model="form.action" placeholder="请输入操作内容"></el-input>
                </el-form-item>
                <el-form-item prop="startDate" label="日期">
                    <el-date-picker
                        v-model="form.startDate"
                        type="date"
                        placeholder="请选择开始日期"
                        format="yyyy 年 MM 月 dd 日"
                        value-format="yyyy-MM-dd 00:00:00"
                    ></el-date-picker>
                    <span> - </span>
                    <el-date-picker
                            v-model="form.endDate"
                            type="date"
                            placeholder="请选择结束日期"
                            format="yyyy 年 MM 月 dd 日"
                            value-format="yyyy-MM-dd 23:59:59"
                            :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.startDate)}}"
                    ></el-date-picker>
                </el-form-item>
                <el-form-item prop="status" label="状态">
                    <el-select v-model="form.status" placeholder="请选择" clearable class="w-100">
                        <el-option value="1" label="成功"></el-option>
                        <el-option value="2" label="失败"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="search">查询</el-button>
                </el-form-item>
            </el-form>
        </el-col>
        <el-col>
            <el-table :data="list" stripe>
                <el-table-column prop="USER_ID" label="用户ID"></el-table-column>
                <el-table-column prop="OPR_NAME" label="用户名"></el-table-column>
                <el-table-column prop="OPR_ACTION" label="操作内容"></el-table-column>
                <el-table-column prop="OPR_IP" label="操作ID"></el-table-column>
                <el-table-column prop="OPR_TIME" label="操作时间"></el-table-column>
                <el-table-column prop="OPR_STATUS" label="操作状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.OPR_STATUS == 1">成功</span>
                        <span v-else-if="scope.row.OPR_STATUS == 2">失败</span>
                        <span v-else>未知({{scope.row.OPR_STATUS}})</span>
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
    </page>
</template>

<script>
    export default {
        name: "operate-log-list",
        data() {
            return {
                form: {
                    userId: '',
                    action: '',
                    startDate: '',
                    endDate: '',
                    status: '',

                    page: 1,
                    pageSize: 10,
                },
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
                api.get('operate/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(pageSize){
                this.form.page = 1;
                this.form.pageSize = pageSize;
                this.getList();
            }
        },
        created() {
            this.form.startDate = moment().subtract(1, 'month').format("YYYY-MM-DD 00:00:00");
            this.form.endDate = moment().format("YYYY-MM-DD 23:59:59");
            this.getList();
        }
    }
</script>

<style scoped>

</style>
