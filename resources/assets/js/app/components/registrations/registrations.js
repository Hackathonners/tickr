/* ============
 * Registrations Component
 * ============
 *
 * Shows registrations
 */
import $ from 'jquery';
import store from 'app/store/index';
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
        deleteRegistration: null,
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

    destroyRegistration(registration) {
      this.$set(this.state, 'busy', true);
      RegistrationService.destroy(registration.id).then(() => {
        $('#delete-registration').modal('hide');
        const i = this.registrations.indexOf(registration);
        if (i !== -1) {
          this.registrations.splice(i, 1);
        }
        store.dispatch('notify', {
          text: 'A inscrição foi apagada com sucesso.',
          type: 'success',
        });
      }).catch(() => {
        store.dispatch('notify', {
          text: 'Ocorreu um erro inesperado. Por favor, tente mais tarde.',
          type: 'danger',
        });
      }).then(() => {
        this.$set(this.state, 'busy', false);
      });
    },

    destroy(registration) {
      this.state.deleteRegistration = registration;
      $('#delete-registration').modal('show');
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
    Submit: require('app/components/submit/submit.vue'),
    Spinner: require('app/components/spinner/spinner.vue'),
  },
};
