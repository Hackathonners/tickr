import Vue from 'vue';
import App from './components/App.vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

Vue.use(VueRouter)
Vue.use(VueResource)

/* eslint-disable no-new */

Vue.http.options.root = '/api/v1';
Vue.http.options.emulateHTTP = true;

var router = new VueRouter({
    history: true,
    root: '/'
});

router.map({
    '/': {
        name: 'events',
        component: require('./components/Events.vue')
    },
    '/events/:id': {
        name: 'events.show',
        component: require('./components/Event.vue'),
    }
});

router.start(App, 'body')
