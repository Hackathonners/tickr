/* ============
 * Registrations Component
 * ============
 *
 * Shows registrations
 */
import RegistrationService from 'app/services/registration/index';

export default {
  props: {
    eventId: {
      required: true,
    },
    paginate: {
      required: false,
      default: true,
    },
    limit: {
      required: false,
      type: Number,
      default: 20,
    },
  },

  data() {
    return {
      // Results data
      registrations: [],

      // Component status
      state: {
        pagination: {},
        loading: true,
      },
    };
  },

  mounted() {
    this.loadRegistrations(this.$route.query.page);
  },

  methods: {
    loadRegistrations(page = 1) {
      this.$set(this.state, 'loading', true);
      RegistrationService.list(this.eventId, page, this.limit).then((registrations) => {
        this.$set(this, 'registrations', registrations.data);
        this.$set(this.state, 'pagination', registrations.meta.pagination);
        this.$set(this.state, 'loading', false);
      });
    },
  },

  watch: {
    '$route.query.page': {
      handler(value) {
        this.loadRegistrations(value);
      },
    },
  },

  components: {
    Paginator: require('app/components/paginator/paginator.vue'),
    Spinner: require('app/components/spinner/spinner.vue'),
  },
};
