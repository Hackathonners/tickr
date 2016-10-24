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
    }
  },

  computed: {
    disabled: function () {
      return this.busy || this.disable
    }
  }
};
