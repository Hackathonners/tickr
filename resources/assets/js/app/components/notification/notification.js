/* ============
 * Notification Component
 * ============
 *
 * Shows a notification on top of page
 */

export default {
  data() {
    return {
      timer: null,
    };
  },

  mounted() {
    this.setTimer();
  },

  computed: {
    notificationType() {
      return this.$store.state.notification.data.type;
    },
  },

  methods: {
    setTimer() {
      const notification = this.$store.state.notification.data.type;
      const timeout = notification.timeout ? notification.timeout : true;

      if (timeout) {
        const delay = notification.delay || 5000;

        this.timer = setTimeout(() => {
          this.close();
        }, delay);
      }
    },

    close() {
      clearTimeout(this.timer);
      this.$store.dispatch('clearNotification');
    },
  },

  watch: {
    '$store.state.notification.data': {
      handler() {
        clearTimeout(this.timer);
        this.setTimer();
      },
    },
  },
};
