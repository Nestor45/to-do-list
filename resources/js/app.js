
require('./bootstrap');

import '@mdi/font/css/materialdesignicons.css'

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'Vuex';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

import {routes} from './routes'
import StoreData from './stores'
import MainApp from './components/MainApp.vue'
import {initialize} from './helpers/general';

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Vuetify)

const store = new Vuex.Store(StoreData)
const vuetify = new Vuetify

const router = new VueRouter({
    routes,
    mode: 'history'
})
initialize(store, router);

// window.Vue = require('vue').default;


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const app = new Vue({
    el: '#app',
    router,
    store,
    vuetify,
    components:{
        MainApp,
    }
});
