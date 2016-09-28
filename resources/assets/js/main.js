import Vue from 'vue'
import App from './App.vue'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'
import moment from 'moment'
import 'moment/locale/pt'

// Import interceptos
import ServerError from './interceptors/ServerErrorInterceptor.js'
import UnprocessableEntityError from './interceptors/UnprocessableEntityErrorInterceptor.js'

// Set moment locale
moment.locale('pt')

Vue.use(VueRouter)
Vue.use(VueResource)

/* eslint-disable no-new */

Vue.http.options.root = '/api/v1'
Vue.http.options.emulateHTTP = true

Vue.http.interceptors.push(ServerError)
Vue.http.interceptors.push(UnprocessableEntityError)

var router = new VueRouter({
  history: true,
  root: '/'
})

router.map({
  '/events': {
    name: 'events',
    component: require('./components/Events/Events.vue')
  },
  '/events/create': {
    name: 'events.create',
    component: require('./components/Events/CreateEvent.vue')
  },
  '/events/:id': {
    name: 'events.show',
    component: require('./components/Events/Event.vue')
  },
  '/events/:id/registrations/create': {
    name: 'registrations.create',
    component: require('./components/Registrations/CreateRegistration.vue')
  },
  '/events/:id/registrations': {
    name: 'registrations',
    component: require('./components/Registrations/Registrations.vue')
  },
  '*': {
    component: require('./components/Errors/404.vue')
  }
})

router.start(App, 'body')
