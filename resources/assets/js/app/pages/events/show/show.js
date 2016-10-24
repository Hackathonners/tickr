/* ============
 * Events Index Page
 * ============
 *
 * Page where the user can view the account information
 */
import moment from 'moment'
import EventService from 'app/services/event'

export default {
  data () {
    return {
      event: {
        stats: {
          income: 0,
          participations: 0,
          registrations: 0,
          registration_types: []
        },
        registration_types: {
          data: []
        }
      },

      state: {
        loading: false,
      },
    }
  },
  mounted () {
    this.loadEvent()
  },
  methods: {
    loadEvent () {
      this.$set(this.state, 'loading', true);
      EventService.get(this.$route.params.id, true).then(event => {
        this.$set(this, 'event', event.data)
      }).catch(() => {
        store.dispatch('notify', {
          text: 'Ocorreu um erro inesperado. Por favor, tente mais tarde.',
          type: 'danger'
        });
      }).then(() => {
        this.$set(this.state, 'loading', false);
      });
    },
    getRegistrationTypeStats (registrationTypeId, statsField) {
      const registrationTypeStats = this.event.stats.registration_types.find(r => r.id === registrationTypeId)
      return registrationTypeStats && statsField in registrationTypeStats ? registrationTypeStats[statsField] : 0
    },
    getRegistrationTypeParticipationRatio (registrationTypeId) {
      return this.getRegistrationTypeStats(registrationTypeId, 'participations') / this.getRegistrationTypeStats(registrationTypeId, 'registrations')
    }
  },
  computed: {
    participationRatio: function () {
      return (this.event.stats.participations / this.event.stats.registrations)
    }
  },
  components: {
    VLayout: require('app/layouts/default/default.vue'),
    Paginator: require('app/components/paginator/paginator.vue'),
    Spinner: require('app/components/spinner/spinner.vue'),
    Registrations: require('app/components/registrations/registrations.vue')
  },
};
