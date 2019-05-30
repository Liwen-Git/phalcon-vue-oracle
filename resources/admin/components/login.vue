<template>
    <div class="login-container">
        <transition name="form-fade" mode="in-out">
            <div class="login-panel">
                <div class="login-logo">
                    <span>账务系统后台</span>
                </div>
                <div class="login-form" v-loading="autoLoginLoading" element-loading-text="登录中">
                    <el-form :model="form" :rules="formRules" ref="form" @keyup.native.enter="doLogin" label-width="55px">
                        <el-form-item prop="account" label="账号">
                            <el-input type="text" v-model="form.account" placeholder="账号"></el-input>
                        </el-form-item>
                        <el-form-item prop="password" label="密码">
                            <el-input type="password" v-model="form.password" placeholder="密码"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" v-loading="buttonLoading" :disabled="buttonLoading" @click.native.prevent="doLogin" >登 录</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import {mapState, mapActions, mapMutations} from 'vuex';

    export default {
        name: "login",
        data() {
            return {
                autoLoginLoading: false,
                buttonLoading: false,
                form: {
                    account: '',
                    password: '',
                },
                formRules: {
                    account: [
                        {required: true, message: '请输入账号', trigger: 'blur'}
                    ],
                    password: [
                        {required: true, message: '密码不能为空', trigger: 'blur'}
                    ]
                },
            }
        },
        methods: {
            ...mapActions([
                'storeUserAndMenus',
            ]),
            ...mapMutations('navTabs', [
                'resetTabList',
            ]),
            doLogin() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        api.post('/self/login', this.form).then(data => {
                            let res = {
                                user: data.user,
                                menus: data.menus,
                                rules: data.rules,
                                forbiddenRules: data.forbiddenRules,
                            };
                            this.storeUserAndMenus(res);
                            this.resetTabList();
                            this.$router.push('/welcome');
                        })
                    }
                })
            }
        },
        computed: {
            ...mapState (
                [
                    'user',
                ]
            )
        },
        created() {
            if (this.user) {
                this.$router.replace('/welcome');
            }
        }
    }
</script>

<style lang="less" scoped>
    .login-container {
        width: 100%;
        top: 0;
        left: 0;
        bottom: 0;
        position: absolute;
        background-image: url(../../assets/images/login-bg.png);
        background-repeat: no-repeat;
        background-size: cover;
        overflow: hidden;
    }
    .login-panel {
        position: absolute;
        top: 30%;
        left: 65%;
        .login-logo {
            text-align: center;
            margin: 20px auto;
            span {
                font-size: 25px;
                color: #fff;
                vertical-align: text-bottom;
                display: inline-block;
                text-transform: uppercase;
            }
        }
    }
    .login-form {
        background-color: #fff;
        padding: 45px 25px 5px;
        box-shadow: 0 0 100px rgba(0, 0, 0, 0.8);
        border-radius: 10px;
    }
</style>
