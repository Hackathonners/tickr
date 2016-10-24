/* ============
 * Event Create Page
 * ============
 *
 * Page where the user can view the account information
 */
import moment from 'moment'
import store from 'app/store'
import EventService from 'app/services/event'

export default {
  data () {
    return {
      event: {
        title: '',
        description: '',
        place: '',
        start_at: '',
        end_at: '',
        registration: [{
          type: 'Normal',
          price: '',
          fine: ''
        }]
      },

      state: {
        busy: false,
        error: {
          messages: [],
        },
        dates: {
          format: 'YYYY-MM-DD HH:mm:ss',
          start_date: null,
          end_date: null,
          start_time: null,
          end_time: null
        },
      },
    }
  },
  mounted () {
    this.resetErrors();
  },
  methods: {
    store () {
      this.$set(this.state, 'busy', true);
      this.resetErrors()
      EventService.store(this.event).then(event => {
        store.dispatch('notify', {
          text: 'O evento foi criado com sucesso.',
          type: 'success'
        });
        // this.$router.go({ name: 'events.show', params: { id: event.data.id } })
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
    addRegistrationType () {
      const registrationType = {
        type: '',
        price: '',
        fine: ''
      };

      this.event.registration.push(registrationType)
    },
    removeRegistrationType (index) {
      this.event.registration.splice(index, 1)
    },
    resetErrors () {
      this.state.error = {
        messages: []
      }

      return this.error
    }
  },
  watch: {
    'state.dates': {
      deep: true,
      handler (dates) {
        const format = 'YYYY-MM-DD HH:mm'
        const lastStartDate = this.event.start_at
        const lastEndDate = this.event.end_at

        this.event.start_at = this.event.end_at = ''

        if (this.state.dates.start_date && this.state.dates.start_time) {
          const date = moment(`${this.state.dates.start_date} ${this.state.dates.start_time}`, format)
          this.event.start_at = date.isValid() ? date.format(this.state.dates.format) : lastStartDate
        }

        if (this.state.dates.end_date && this.state.dates.end_time) {
          const date = moment(`${this.state.dates.end_date} ${this.state.dates.end_time}`, format)
          this.event.end_at = date.isValid() ? date.format(this.state.dates.format) : lastEndDate
        }
      }
    }
  },
  components: {
    VLayout: require('app/layouts/default/default.vue'),
    Submit: require('app/components/submit/submit.vue'),
    DatePicker: require('app/components/datepicker/datepicker.vue'),
  },
};
