
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Set packages locale according to document language.
 */
moment.locale(document.documentElement.lang);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VueRouter from 'vue-router';
import VuexRouterSync from 'vuex-router-sync';
import routes from 'app/routes';
import store from 'app/store';

Vue.use(VueRouter);
Vue.use(VuexRouterSync);

const router = new VueRouter({
  mode: 'history',
  routes,
});

VuexRouterSync.sync(store, router);

Vue.component('app', require('./app/components/App.vue'));

window.axios.defaults.baseURL = 'http://karina.dev/api/v1';
window.axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    'X-Requested-With': 'XMLHttpRequest'
};

require('app/utils/filters/Date');
require('app/utils/filters/Number');

const app = new Vue({
    el: '#app',
    router,
    store,
});
