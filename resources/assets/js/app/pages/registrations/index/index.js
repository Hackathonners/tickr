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
      // Results data
      event: {},

      state: {
        loading: false,
      }
    }
  },

  mounted () {
    this.loadEvent()
  },

  methods: {
    loadEvent () {
      this.$set(this.state, 'loading', true);
      EventService.get(this.$route.params.id).then(event => {
        this.$set(this, 'event', event.data)
      }).catch(response => {
        store.dispatch('notify', {
          text: 'Ocorreu um erro inesperado. Por favor, tente mais tarde.',
          type: 'danger'
        });
      }).then(() => {
        this.$set(this.state, 'loading', false);
      })
    }
  },

  components: {
    VLayout: require('app/layouts/default/default.vue'),
    Spinner: require('app/components/spinner/spinner.vue'),
    Registrations: require('app/components/registrations/registrations.vue')
  }
}
