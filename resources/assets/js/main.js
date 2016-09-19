import moment from 'moment'
import 'moment/locale/pt'

import Vue from 'vue'
import App from './App.vue'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'

// Set moment language
moment.locale('pt')

Vue.use(VueRouter)
Vue.use(VueResource)

/* eslint-disable no-new */

Vue.http.options.root = '/api/v1'
Vue.http.options.emulateHTTP = true

var router = new VueRouter({
  history: true,
  root: '/'
})

router.map({
  '/': {
    name: 'events',
    component: require('./components/Events.vue')
  },
  '/events/create': {
    name: 'events.create',
    component: require('./components/CreateEvent.vue')
  },
  '/events/:id': {
    name: 'events.show',
    component: require('./components/Event.vue')
  },
  '/events/:id/registrations/create': {
    name: 'registrations.create',
    component: require('./components/CreateRegistration.vue')
  },
  '/events/:id/registrations': {
    name: 'registrations',
    component: require('./components/Registrations.vue')
  },
  '*': {
    component: require('./components/Errors/404.vue')
  }
})

router.alias({
  '/events': '/'
})

router.start(App, 'body')
