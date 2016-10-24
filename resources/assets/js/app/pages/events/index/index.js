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
      events: [],

      state: {
        loading: false,
        tab: 'active',
        format: 'YYYY-MM-DD HH:mm:ss',
        pagination: {
          links: {
            next: null,
            prev: null
          }
        }
      },
    }
  },
  mounted() {
    this.loadEvents(this.$route.query.page, this.$route.query.filter)
  },
  methods: {
    loadEvents (page, filter) {
      this.$set(this.state, 'loading', true);
      this.$set(this.state, 'tab', filter);
      EventService.list(page, filter).then(events => {
        this.$set(this, 'events', events.data)
        this.$set(this.state, 'pagination', events.meta.pagination)
      }).then(() => {
        this.$set(this.state, 'loading', false);
      });
    },
    isRunning (event) {
      return moment().isBetween(event.start_at, event.end_at)
    },
    isPast (event) {
      return moment().isAfter(event.end_at)
    },
    isFuture (event) {
      return moment().isBefore(event.start_at)
    },
    isEdited (event) {
      return !moment(event.updated_at).isSame(event.created_at)
    }
  },
  computed: {
    filterActive () {
      return ['past'].indexOf(this.state.tab) < 0
    },

    filterPast () {
      return this.state.tab === 'past'
    }
  },
  watch: {
    '$route.query': function (newValue, oldValue) {
      let filter = oldValue.filter
      let page = newValue.page

      // Reset page on filter change
      if (filter !== newValue.filter) {
        filter = newValue.filter
        page = 1
      }

      this.loadEvents(page, filter)
    }
  },
  components: {
    VLayout: require('app/layouts/default/default.vue'),
    Paginator: require('app/components/paginator/paginator.vue'),
    Spinner: require('app/components/spinner/spinner.vue')
  },
};
