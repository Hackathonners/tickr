<template>
  <div class="row page-header">
    <div class="col-xs-8">
      <span class="page-title">Eventos</span>
    </div>
    <div class="col-xs-4">
      <a class="page-action btn btn-primary pull-right" v-link="{ name: 'events.create' }">Novo evento</a>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">

      <!-- Events Filter -->
      <ul class="nav nav-tabs">
        <li :class="{ 'active': filterActive }">
          <a v-link="{ name: 'events' }">Eventos ativos</a>
        </li>
        <li :class="{ 'active': filterPast }">
          <a v-link="{ name: 'events', query: { filter: 'past' } }">Eventos passados</a>
        </li>
      </ul>

      <!-- Events List -->
      <table class="table table--noheading table--events">
        <tbody>
          <tr v-show="!$loadingRouteData" v-for="event in events">

            <!-- Event details -->
            <td class="col-md-6 event-info">
              <div class="event-info__name">
                <a v-link="{ name: 'events.show', params: { id: event.id }}">{{ event.title }}</a>
              </div>
              <div class="event-info__place text-muted">
                {{ event.place }} · Criado {{ event.created_at | human_diff }}
                <span v-show="isEdited(event)"> · Editado {{ event.updated_at | human_diff }}</span>
              </div>
            </td>

            <!-- Event status -->
            <td class="col-md-3 event-info">
              <div v-if="isRunning(event)" class="event-info__status event-info__status--running">
                <span class="status__title text-primary">A decorrer</span>
                <div class="status__details text-muted">
                  Termina em {{ event.end_at | human_diff true }}
                </div>
              </div>
              <div v-if="isPast(event)" class="event-info__status event-info__status--waiting">
                <span class="status__title">Terminou {{ event.start_at | human_diff }}</span>
                <div class="status__details text-muted">
                  {{ event.start_at | date 'dddd' }}, {{ event.start_at | date 'D MMMM YYYY, HH:mm' }}
                </div>
              </div>
              <div v-if="isFuture(event)" class="event-info__status event-info__status--waiting">
                <span class="status__title">Inicia em {{ event.start_at | human_diff true }}</span>
                <div class="status__details text-muted">
                  {{ event.start_at | date 'dddd' }}, {{ event.start_at | date 'D MMMM YYYY, HH:mm' }}
                </div>
              </div>
            </td>

            <!-- Event ticket status -->
            <td class="col-md-3 event-info">
              <span v-if="event.registrations">{{ event.registrations }}</span>
              <span v-else>Sem bilhetes vendidos</span>
            </td>
          </tr>
        </tbody>
      </table>
      <paginator v-show="!$loadingRouteData" :pagination.sync="pagination" :callback="loadEvents"></paginator>
      <loading :loading="$loadingRouteData"></loading>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  import Loading from './Util/Loading.vue';
  import Paginator from './Layout/Paginator.vue';
  import EventService from '../services/EventService.js';
  import '../filters/Date';

  export default {
    data() {
      return {
        // Status data
        tabs: {
          visibility: '',
          page: 1,
        },

        // Results data
        format: 'YYYY-MM-DD HH:mm:ss',
        events: [],
        pagination: {
          links: {
            next: null,
            prev: null,
          }
        },
      }
    },
    created() {
      // Set visibility tab
      this.tabs.visibility = this.$route.query.filter;
      this.tabs.page = this.$route.query.page;
    },
    ready() {
      this.loadEvents(this.tabs.page);
    },
    methods: {
      loadEvents(page = 1) {
        this.$loadingRouteData = true;
        EventService.list(page, this.tabs.visibility).then(events => {
          this.$set('events', events.data);
          this.$set('pagination', events.meta.pagination);
          this.$set('tabs.page', events.meta.pagination.current_page);
          this.$loadingRouteData = false;
        });
      },
      isRunning(event) {
        return moment().isBetween(event.start_at, event.end_at);
      },
      isPast(event) {
        return moment().isAfter(event.end_at);
      },
      isFuture(event) {
        return moment().isBefore(event.start_at);
      },
      isEdited(event) {
        return !moment(event.updated_at).isSame(event.created_at);
      }
    },
    computed: {
      filterActive() {
        return ['past'].indexOf(this.tabs.visibility) < 0;
      },
      filterPast() {
        return this.tabs.visibility == 'past'
      },
    },
    components: {
      Loading, Paginator
    },
    route: {
      canReuse: false
    },
  };
</script>
