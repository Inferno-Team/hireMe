require('./bootstrap');
import 'material-design-icons-iconfont/dist/material-design-icons.css';

window.Vue = require('vue').default;
import App from './layout/App.vue';
import vuetify from './vuetify';
import { routes } from './routes';
import VueRouter from 'vue-router';
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
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