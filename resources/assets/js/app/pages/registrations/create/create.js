/* ============
 * Event Create Page
 * ============
 *
 * Page where the user can view the account information
 */
import moment from 'moment'
import store from 'app/store'
import EventService from 'app/services/event'
import RegistrationService from 'app/services/registration'

export default {
  data () {
    return {
      // Results data
      event: {
        name: '',
        registration_types: {
          data: []
        }
      },

      // Form data
      registration: {},

      // Component status
      state: {
        loading: true,
        busy: false,
        error: {
          messages: [],
        }
      },
    }
  },
  mounted () {
    this.resetRegistrationState()
    this.loadEvent()
  },
  methods: {
    loadEvent () {
      this.$set(this.state, 'loading', true);
      EventService.get(this.$route.params.id, true).then(event => {
        this.$set(this, 'event', event.data)
      }).catch(response => {
        // Handle
      }).then(() => {
        this.$set(this.state, 'loading', false);
      })
    },
    resetRegistrationState () {
      this.registration = {
        name: null,
        email: null,
        fined: moment().isBetween(this.event.start_at, this.event.end_at, 'day'),
        registration_type: '',
        notes: null
      }

      return this.registration;
    },
    store () {
      this.resetErrors()
      this.$set(this.state, 'busy', true);
      RegistrationService.store(this.event.id, this.registration).then(registration => {
        store.dispatch('notify', {
          text: 'A inscrição foi adicionada com sucesso.',
          type: 'success'
        });
        this.resetRegistrationState()
      }).catch(response => {
        if (response.error.http_code === 422) {
          this.$set(this.state, 'error', response.error);
          store.dispatch('notify', {
            text: 'Por favor, verifique os dados introduzidos.',
            type: 'danger'
          });
          return;
        }

        store.dispatch('notify', {
          text: 'Ocorreu um erro inesperado. Por favor, tente mais tarde.',
          type: 'danger'
        });
      }).then(() => {
        this.$set(this.state, 'busy', false);
        window.scrollTo(0, 0)
      })
    },
    getRegistrationTypeData (registrationTypeId, field) {
      const registrationType = this.event.registration_types.data.find(r => r.id === registrationTypeId)
      return registrationType && field in registrationType ? registrationType[field] : 0
    },
    getPaidValue () {
      const type = this.registration.registration_type

      if (typeof type === 'undefined' || !type) {
        return 0
      }

      const registrationType = this.event.registration_types.data.find(r => r.id === type)
      let value = registrationType.price
      value += this.registration.fined ? registrationType.fine : 0

      return value
    },
    resetErrors () {
      this.state.error = {
        messages: []
      }

      return this.state.error
    }
  },
  components: {
    VLayout: require('app/layouts/default/default.vue'),
    Spinner: require('app/components/spinner/spinner.vue'),
    Submit: require('app/components/submit/submit.vue')
  }
};
