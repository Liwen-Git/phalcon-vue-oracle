import './bootstrap';

/**
 * 引入vue组件
 */
import Vue from 'vue';
window.Vue = Vue;

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

import NProgress from 'nprogress';

import routes from './routes/index';
import VueRouter from 'vue-router';
const router = new VueRouter({
    mode: 'history',
    base: '/admin',
    routes
});

router.beforeEach((to, from, next) => {
    NProgress.start();
    console.log('to', to);
    next();
});
router.afterEach(() => {
    NProgress.done();
});

window.router = router;
Vue.use(VueRouter);

import App from './App.vue';
new Vue({
    el: '#app',
    template: '<App/>',
    router,
    components: {App}
}).$mount('#app');
