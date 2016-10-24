/* ============
 * Routes File
 * ============
 *
 * The routes and redirects are defined in this file
 */


/**
 * The routes
 *
 * @type {object} The routes
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
