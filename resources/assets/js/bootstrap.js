import Vue from 'vue';
import VueResource from 'vue-resource';
import VuexRouterSync from 'vuex-router-sync';
import VueRouter from 'vue-router';
import VueI18n from 'vue-i18n';
import routes from 'app/routes';
import store from 'app/store/index';
import locale from 'app/locale/index';
import { csrfToken } from 'app/utils/functions';

/* ============
 * Vue
 * ============
 *
 * Vue.js is a library for building interactive web interfaces.
 * It provides data-reactive components with a simple and flexible API.
 *
 * http://rc.vuejs.org/guide/
 */

Vue.config.debug = process.env.NODE_ENV !== 'production';

/* ============
 * Vue Resource
 * ============
 *
 * Vue Resource provides services for making web requests and handle
 * responses using a XMLHttpRequest or JSONP.
 *
 * https://github.com/vuejs/vue-resource/tree/master/docs
 */

Vue.use(VueResource);

Vue.http.headers.common.Accept = 'application/json';
Vue.http.options.root = '/api/v1';
Vue.http.interceptors.push((request, next) => {
  next((response) => {
    // When the session expires, send to login
    if (response.status === 401) {
      location.href = '/login';
    }
  });
});

// Laravel CSRF token interceptor
Vue.http.interceptors.push((request, next) => {
  const token = csrfToken();
  if (token) {
    request.headers.set('X-CSRF-TOKEN', token);
  }

  next();
});

/* ============
 * Vue Router
 * ============
 *
 * The official Router for Vue.js. It deeply integrates with Vue.js core
 * to make building Single Page Applications with Vue.js a breeze.
 *
 * http://router.vuejs.org/en/index.html
 */

Vue.use(VueRouter);

export const router = new VueRouter({
  mode: 'history',
  routes,
});

VuexRouterSync.sync(store, router);

Vue.router = router;


/* ============
 * Vue i18n
 * ============
 *
 * Internationalization plugin of Vue.js
 *
 * https://kazupon.github.io/vue-i18n/
 */
Vue.use(VueI18n);

Vue.config.lang = 'en';

Object.keys(locale).forEach((lang) => {
  Vue.locale(lang, locale[lang]);
});

/* ============
 * Filters
 * ============
 *
 */
require('app/utils/filters/Date');
require('app/utils/filters/Number');

export default {
  router,
};
