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
    NProgress.start();
    console.log('to', to);
    next();
});
// 全局后置钩子
router.afterEach(() => {
    NProgress.done();
});
// 把vue-router赋值给window全局变量router
window.router = router;
Vue.use(VueRouter);

/**
 * Vue函数创建Vue实例
 */
import App from './App.vue';
new Vue({
    el: '#app',
    template: '<App/>',
    router,
    components: {App}
}).$mount('#app');
