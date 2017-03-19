import VueRouter from 'vue-router';
import VuexRouterSync from 'vuex-router-sync';
import routes from 'app/routes';
import store from 'app/store';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-default/index.css';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Set packages locale according to document language.
 */
window.moment.locale(document.documentElement.lang);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Only starts app if target element exists
if (document.getElementById('app')) {
  Vue.use(VueRouter);
  Vue.use(VuexRouterSync);
  Vue.use(ElementUI);

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
    'X-Requested-With': 'XMLHttpRequest',
  };
  window.axios.interceptors.request.use(response => response, (error) => {
    if (error.response.status === 401) {
      window.location.href = '/login';
    }
    return Promise.reject(error);
  });

  require('app/utils/filters/Date');
  require('app/utils/filters/Number');

  const app = new Vue({
    router,
    store,
  });

  app.$mount('#app');
}
