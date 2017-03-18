/**
 * These are the entrypoints of the application, where each path is related
 * to each existent component. In this file, add every route.
 */

export default [
  {
    path: '/events',
    name: 'events.index',
    component: require('app/pages/events/index/index.vue'),
  },
  {
    path: '/events/create',
    name: 'events.create',
    component: require('app/pages/events/create/create.vue'),
  },
  {
    path: '/events/:id',
    name: 'events.show',
    component: require('app/pages/events/show/show.vue'),
  },
  {
    path: '/events/:id/registrations',
    name: 'registrations.index',
    component: require('app/pages/registrations/index/index.vue'),
  },
  {
    path: '/events/:id/registrations/create',
    name: 'registrations.create',
    component: require('app/pages/registrations/create/create.vue'),
  },

  {
    path: '/',
    redirect: '/events',
  },
  {
    path: '/*',
    redirect: '/events',
  },
];
