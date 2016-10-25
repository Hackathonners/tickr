import moment from 'moment';
import Vue from 'vue';
import * as App from './app/index.vue';

/* ============
 * Main File
 * ============
 *
 * Will initialize the application
 */
moment.locale('pt');

// Bootstrap & jQuery
window.$ = window.jQuery = require('jquery');
require('jquery-ui');
require('jquery-ui/ui/widgets/datepicker');
require('bootstrap-sass/assets/javascripts/bootstrap');

require('./bootstrap');

new Vue(App).$mount('#tickr-app');
