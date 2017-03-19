/* ============
 * Default Layout
 * ============
 *
 * Used for the home and account pages
 *
 * Layouts are used to store a lot of shared code.
 * This way the app stays clean.
 */

export default {
  mounted() {
    document.querySelector('form#logout input[name=_token]')
      .value = $('meta[name="csrf-token"]').attr('content');
  },

  methods: {
    logout() {
      document.querySelector('form#logout').submit();
    },
  },

  components: {
    Notification: require('app/components/notification/notification.vue'),
  },
};
