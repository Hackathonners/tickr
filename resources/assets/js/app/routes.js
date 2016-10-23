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
    path: '/',
    redirect: '/events',
  },
  {
    path: '/*',
    redirect: '/events',
  },
];
