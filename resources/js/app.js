require('./bootstrap');
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import { longClickDirective } from 'vue-long-click'
window.Vue = require('vue').default;
import App from './layout/App.vue';
import vuetify from './vuetify';
import { routes } from './routes';
import VueRouter from 'vue-router';

const longClickInstance = longClickDirective({ delay: 400, interval: 50 })
Vue.directive('longclick', longClickInstance)
Vue.use(VueRouter);
const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    el: '#applicationDiv',
    vuetify: vuetify,
    components: { App },
    router
});