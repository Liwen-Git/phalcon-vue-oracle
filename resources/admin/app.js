import './bootstrap';

import Vue from 'vue';
window.Vue = Vue;

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

import routes from './routes/index';
import VueRouter from 'vue-router';
const router = new VueRouter({
    mode: 'history',
    base: '/admin',
    routes
});

import App from './App.vue';
new Vue({
    el: '#app',
    router,
    components: {App}
}).$mount('#app');
