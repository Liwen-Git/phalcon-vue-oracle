import './bootstrap';

/**
 * 引入vue组件
 */
import Vue from 'vue';
window.Vue = Vue;

/**
 * 引入element-ui组件 和 [样式: import 'element-ui/lib/theme-chalk/index.css'; 在webpack.mix.js中引入css]
 */
import ElementUI from 'element-ui';
Vue.use(ElementUI);

/**
 * web 进度条 [css: import 'nprogress/nprogress.css'; 在webpack.mix.js中引入]
 */
import NProgress from 'nprogress';

/**
 * 引入vue-router路由组件，同时配置路由 并引入自己的路由
 */
import routes from './routes/index';
import VueRouter from 'vue-router';
const router = new VueRouter({
    mode: 'history',
    base: '/admin',
    routes
});
// 注册一个全局前置守卫
router.beforeEach((to, from, next) => {
    // home.vue 中的内容部分页面全局loading
    store.dispatch('openGlobalLoading');
    NProgress.start();
    next();
});
// 全局后置钩子
router.afterEach(() => {
    store.dispatch('closeGlobalLoading');
    NProgress.done();
});
// 把vue-router赋值给window全局变量router [也可以使用this.$router调用]
window.router = router;
Vue.use(VueRouter);

/**
 * 引入 vuex 集中式管理组件状态 [在页面中使用this.$store调用,因为实例Vue的时候引入了]
 */
import store from './store';
window.store = store;

/**
 * 引入自定义全局组件 Page => 主要包含 面包屑
 */
import Page from './components/page';
Vue.component('Page', Page);

/**
 * 引入请求api
 */
window.baseApiUrl = '/';
import api from '../assets/js/api';
window.api = api;

/**
 * Vue函数创建Vue实例
 */
import App from './App.vue';
new Vue({
    el: '#app',
    template: '<App/>',
    router,
    store,
    components: {App}
}).$mount('#app');
