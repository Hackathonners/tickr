/* ============
 * Registrations Component
 * ============
 *
 * Shows registrations
 */
import RegistrationService from 'app/services/registration'

export default {
  props: {
    eventId: {
      required: true
    },
    paginate: {
      required: false,
      default: true
    },
    limit: {
      required: false,
      type: Number,
      default: 20
    },
    action: {
      required: true,
      type: Object
    }
  },

  data () {
    return {
      // Results data
      registrations: [],

      // Component status
      state: {
        pagination: {},
        loading: false
      }
    }
  },

  mounted () {
    this.loadRegistrations(this.$route.query.page)
  },

  methods: {
    loadRegistrations (page = 1) {
      this.$set(this.state, 'loading', true)
      RegistrationService.list(this.eventId, page, this.limit).then(registrations => {
        this.$set(this, 'registrations', registrations.data)
        this.$set(this.state, 'pagination', registrations.meta.pagination)
        this.$set(this.state, 'loading', false)
      })
    }
  },

  watch: {
    '$route.query.page': function (newValue, oldValue) {
      this.loadRegistrations(newValue)
    }
  },

  components: {
    // Loading, Paginator
  }
}
