/* ============
 * Submit Component
 * ============
 *
 * todo: add documentation here!
 */

export default {
  props: {
    busy: {
      required: true,
      type: Boolean,
      default: false,
    },
    message: {
      type: String,
      default: 'Sending...',
    },
    disable: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    disabled() {
      return this.busy || this.disable;
    },
  },
};
