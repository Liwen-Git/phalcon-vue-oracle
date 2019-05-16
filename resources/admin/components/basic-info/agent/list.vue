<template>
    <page title="代理商信息">
        <el-form :model="form" ref="form" inline size="small">
            <el-form-item prop="agent_id" label="代理商号">
                <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="agent_name" label="代理商名称">
                <el-select v-model="form.agent_name"
                           clearable
                           filterable
                           remote
                           placeholder="请输入关键字"
                           :remote-method="searchAgentName"
                           :loading="theLoading"
                >
                    <el-option v-for="(item, index) in agentOptions" :key="index" :label="item.agent_name" :value="item.agent_name"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="list" stripe>
            <el-table-column prop="index" label="序号"></el-table-column>
            <el-table-column prop="agent_id" label="代理商号"></el-table-column>
            <el-table-column prop="agent_name" label="代理商名称"></el-table-column>
            <el-table-column prop="agent_level" label="代理商级别">
                <template slot-scope="scope">
                    <span v-if="scope.row.agent_level == 1">一级代理商</span>
                    <span v-else-if="scope.row.agent_level == 2">二级代理商</span>
                    <span v-else-if="scope.row.agent_level == 3">三级代理商</span>
                    <span v-else>未知({{scope.row.agent_level}})</span>
                </template>
            </el-table-column>
            <el-table-column prop="agent_nature" label="代理商类别">
                <template slot-scope="scope">
                    <span v-if="scope.row.agent_nature == 1">公司用户</span>
                    <span v-else-if="scope.row.agent_nature == 2">个体用户</span>
                    <span v-else-if="scope.row.agent_nature == 3">其他</span>
                    <span v-else>未知({{scope.row.agent_nature}})</span>
                </template>
            </el-table-column>
            <el-table-column prop="agent" label="上级机构"></el-table-column>
            <el-table-column prop="create_time" label="注册日期"></el-table-column>
            <el-table-column prop="agent_verify" label="商户认证状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.agent_verify == 1">未认证</span>
                    <span v-else-if="scope.row.agent_verify == 2">已认证</span>
                    <span v-else>未知({{scope.row.agent_verify}})</span>
                </template>
            </el-table-column>
            <el-table-column prop="user_state" label="用户状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.user_state == 1">待激活</span>
                    <span v-else-if="scope.row.user_state == 2">正常</span>
                    <span v-else-if="scope.row.user_state == 3">冻结变更</span>
                    <span v-else-if="scope.row.user_state == 4">全面冻结</span>
                    <span v-else-if="scope.row.user_state == 5">注销</span>
                    <span v-else>未知({{scope.row.user_state}})</span>
                </template>
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button type="text" @click="detail(scope.row.agent_id)">详情</el-button>
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

        <el-dialog title="合同费率信息" :visible.sync="dialogTableVisible" width="80%">
            <el-table :data="gridData">
                <el-table-column prop="index" label="序号"></el-table-column>
                <el-table-column prop="product_name" label="企业产品类型"></el-table-column>
                <el-table-column prop="type" label="收费模式">
                    <template slot-scope="scope">
                        <span v-if="scope.row.type == 1">百分比</span>
                        <span v-else-if="scope.row.type == 2">固定每笔</span>
                        <span v-else-if="scope.row.type == 3">混合收费</span>
                        <span v-else>未知({{scope.row.type}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="fee" label="基准费率">
                    <template slot-scope="scope">
                        <span v-if="scope.row.type == 1">
                            {{scope.row.rate + '%'}}
                        </span>
                        <span v-else-if="scope.row.type == 2">
                            {{parseFloat(scope.row.fixedFee/100) + '元'}}
                        </span>
                        <span v-else-if="scope.row.type == 3">
                            {{scope.row.rate + '% + ' + parseFloat(scope.row.fixedFee/100) + '元'}}
                        </span>
                        <span v-else>??</span>
                    </template>
                </el-table-column>
                <el-table-column prop="calc_prf_min_amt" label="交易分润起价(元)">
                    <template slot-scope="scope">
                        {{scope.row.calc_prf_min_amt ? parseFloat(scope.row.calc_prf_min_amt/100) + '元' : 0}}
                    </template>
                </el-table-column>
                <el-table-column prop="share_pct" label="分润比例(%)"></el-table-column>
                <el-table-column prop="start_time" label="生效时间"></el-table-column>
                <el-table-column prop="end_time" label="结束时间"></el-table-column>
                <el-table-column prop="pri_level" label="优先级"></el-table-column>
                <el-table-column prop="state" label="状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.state == 0">未生效</span>
                        <span v-if="scope.row.state == 1">正常</span>
                        <span v-else-if="scope.row.state == 2">作废</span>
                        <span v-else-if="scope.row.state == 3">过期</span>
                        <span v-else>未知({{scope.row.state}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="memo" label="备注"></el-table-column>
            </el-table>
            <el-pagination
                    layout="sizes, total, prev, pager, next"
                    :page-size="dialogTablePageSize"
                    :total="dialogTableTotal"
                    :current-page.sync="dialogTablePage"
                    @current-change="getDetailList"
                    :page-sizes="[10, 20, 30]"
                    @size-change="changeDetailPageSize"
            ></el-pagination>
        </el-dialog>
    </page>
</template>

<script>
    export default {
        name: "agent-list",
        data() {
            return {
                form: {
                    agent_id: '',
                    agent_name: '',
                    page: 1,
                    page_size: 10,
                },
                total: 0,
                list: [],
                theLoading: false,
                agentOptions: [],

                dialogTableVisible: false,
                gridData: [],
                dialogTablePage: 1,
                dialogTablePageSize: 10,
                dialogTableTotal: 0,
                agentId: '',
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('agent/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(pageSize) {
                this.form.page = 1;
                this.form.page_size = pageSize;
                this.getList();
            },
            searchAgentName(query) {
                if (query !== '') {
                    this.theLoading = true;
                    setTimeout(() => {
                        this.theLoading = false;
                        api.get('agent/query_agent_info', {agent_name: query}).then(data => {
                            this.agentOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.agentOptions = [];
                }
            },
            detail(agentId) {
                this.agentId = agentId;
                this.dialogTablePage = 1;
                this.dialogTablePageSize = 10;
                this.dialogTableVisible = true;
                this.getDetailList();
            },
            getDetailList() {
                let param = {
                    agent_id: this.agentId,
                    page: this.dialogTablePage,
                    pageSize: this.dialogTablePageSize,
                };
                api.get('agent/detail', param).then(data => {
                    this.gridData = data.list;
                    this.dialogTableTotal = data.total;
                })
            },
            changeDetailPageSize(pageSize) {
                this.dialogTablePage = 1;
                this.dialogTablePageSize = pageSize;
                this.getDetailList();
            }
        },
        created() {
            this.getList();
        }
    }
</script>

<style scoped>

</style>
