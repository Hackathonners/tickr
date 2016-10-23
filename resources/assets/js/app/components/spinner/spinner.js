/* ============
 * Spinner Component
 * ============
 *
 * Shows a spinner while data is loading.
 */
import { PulseLoader } from 'vue-spinner/dist/vue-spinner.min'

export default {
  props: {
    loading: {
      type: Boolean,
      required: true
    },
    message: {
      type: String,
      default: 'Loading...'
    }
  },

  components: {
    PulseLoader
  }
}
