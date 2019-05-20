<template>
    <page title="余额查询">
        <el-form :model="form" ref="form" inline size="small">
            <el-form-item prop="merchant_type" label="商户类型">
                <el-select v-model="form.merchant_type">
                    <el-option label="商户" value="1"></el-option>
                    <el-option label="代理商" value="2"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item prop="agent_id" label="代理商号" :class="form.merchant_type == 2 ? 'show-class' : 'none-class'">
                <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="agent_name" label="代理商名称" :class="form.merchant_type == 2 ? 'show-class' : 'none-class'">
                <el-select v-model="form.agent_name"
                           clearable
                           filterable
                           remote
                           placeholder="请输入关键字"
                           :remote-method="searchAgentName"
                           :loading="theAgentLoading"
                >
                    <el-option v-for="(item, index) in agentOptions" :key="index" :label="item.agent_name" :value="item.agent_name"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item prop="merchant_id" label="商户号" :class="form.merchant_type == 1 ? 'show-class' : 'none-class'">
                <el-input type="text" v-model="form.merchant_id" placeholder="请输入商户编号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="merchant_name" label="商户名称" :class="form.merchant_type == 1 ? 'show-class' : 'none-class'">
                <el-select v-model="form.merchant_name"
                           clearable
                           filterable
                           remote
                           placeholder="请输入关键字"
                           :remote-method="searchMerchantName"
                           :loading="theMerchantLoading"
                >
                    <el-option v-for="(item, index) in merchantOptions" :key="index" :label="item.merchant_name" :value="item.merchant_name"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
        </el-form>
    </page>
</template>

<script>
    export default {
        name: "balance-list",
        data() {
            return {
                form: {
                    merchant_type: '1',
                    merchant_id: '',
                    merchant_name: '',
                    agent_id: '',
                    agent_name: '',

                    page: 1,
                    page_size: 10
                },
                total: 0,
                list: [],

                agentOptions: [],
                merchantOptions: [],
                theAgentLoading: false,
                theMerchantLoading: false,
            }
        },
        methods: {
            searchAgentName(query) {
                if (query !== '') {
                    this.theAgentLoading = true;
                    setTimeout(() => {
                        this.theAgentLoading = false;
                        api.get('agent/query_agent_info', {agent_name: query}).then(data => {
                            this.agentOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.agentOptions = [];
                }
            },
            searchMerchantName(query) {
                if (query !== '') {
                    this.theMerchantLoading = true;
                    setTimeout(() => {
                        this.theMerchantLoading = false;
                        api.post('merchant/query_merchant_info', {merchant_name: query}).then(data => {
                            this.merchantOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.merchantOptions = [];
                }
            },
        }
    }
</script>

<style scoped>
    .show-class {
        display: inline-block;
    }
    .none-class {
        display: none;
    }
</style>
