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
    path: '/',
    redirect: '/events',
  },
  {
    path: '/*',
    redirect: '/events',
  },
];
