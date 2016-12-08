/* ============
 * Events Index Page
 * ============
 *
 * Page where the user can view the account information
 */
import $ from 'jquery';
import moment from 'moment';
import store from 'app/store/index';
import EventService from 'app/services/event/index';

export default {
  data() {
    return {
      event: {
        stats: {
          income: 0,
          participations: 0,
          registrations: 0,
          registration_types: [],
        },
        registration_types: {
          data: [],
        },
      },

      state: {
        loading: true,
      },
    };
  },
  mounted() {
    this.loadEvent();
  },
  methods: {
    loadEvent() {
      this.$set(this.state, 'loading', true);
      EventService.get(this.$route.params.id, true).then((event) => {
        this.$set(this, 'event', event.data);
      }).catch((response) => {
        if (response.error.http_code === 404) {
          this.$store.dispatch('notify', {
            text: 'O evento pretendido não existe ou não está disponível.',
            type: 'danger',
          });
          this.$router.push({ name: 'events.index' });
        } else {
          this.$store.dispatch('notify', {
            text: 'Ocorreu um erro inesperado. Por favor, tente mais tarde.',
            type: 'danger',
          });
        }

        // Prevent loading to be hidden
        return Promise.reject();
      }).then(() => {
        this.$set(this.state, 'loading', false);
      });
    },
    getRegistrationTypeStats(registrationTypeId, statsField) {
      const registrationTypeStats = this.event.stats.registration_types
        .find(r => r.id === registrationTypeId);
      return registrationTypeStats && statsField in registrationTypeStats ?
        registrationTypeStats[statsField] : 0;
    },
    getRegistrationTypeParticipationRatio(registrationTypeId) {
      const totalRegistrations = this.getRegistrationTypeStats(
        registrationTypeId,
        'registrations'
      );

      const totalParticipations = this.getRegistrationTypeStats(
        registrationTypeId,
        'participations'
      );

      return totalParticipations / totalRegistrations;
    },
    isPast(event) {
      return moment().isAfter(event.end_at);
    },
    destroy() {
      this.$set(this.state, 'busy', true);
      EventService.destroy(this.event.id).then(() => {
        $('#delete-event').modal('hide');
        this.$router.push({ name: 'events.index' });
        store.dispatch('notify', {
          text: 'O evento foi apagado com sucesso.',
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
  },
  computed: {
    participationRatio() {
      return (this.event.stats.participations / this.event.stats.registrations);
    },
  },
  components: {
    VLayout: require('app/layouts/default/default.vue'),
    Submit: require('app/components/submit/submit.vue'),
    Paginator: require('app/components/paginator/paginator.vue'),
    Spinner: require('app/components/spinner/spinner.vue'),
    Registrations: require('app/components/registrations/registrations.vue'),
  },
};
