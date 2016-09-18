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
          <!-- No results -->
          <tr class="no-results" v-show="!$loadingRouteData && events.length == 0">
            <td class="col-md-12 event-info text-center" colspan="3">
              <span v-show="filterActive">Não existem eventos ativos, <a class="text-primary" v-link="{ name: 'events.create' }">crie um evento</a>.</span>
              <span v-show="filterPast">Não existem eventos passados.</span>
            </td>
          </tr>

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
  import Loading from './Shared/Loading.vue';
  import Paginator from './Shared/Paginator.vue';
  import EventService from '../services/EventService.js';
  import '../filters/Date';

  export default {
    data() {
      return {
        // Status data
        visibility: '',

        // Results data
        format: 'YYYY-MM-DD HH:mm:ss',
        events: [],
        pagination: {
          links: {
            next: null,
            prev: null,
          },
        },
      };
    },

    ready() {
      this.loadEvents(this.$route.query.page, this.$route.query.filter);
    },

    methods: {
      loadEvents(page, filter) {
        this.$loadingRouteData = true;
        this.$set('visibility', filter);
        EventService.list(page, filter).then(events => {
          this.$set('events', events.data);
          this.$set('pagination', events.meta.pagination);
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
        return ['past'].indexOf(this.visibility) < 0;
      },
      filterPast() {
        return this.visibility == 'past';
      },
    },

    watch: {
      '$route.query': function (newValue, oldValue) {
        let filter = oldValue.filter;
        let page = newValue.page;

        // Reset page on filter change
        if( filter != newValue.filter) {
          filter = newValue.filter;
          page = 1;
        }

        this.loadEvents(page, filter);
      },
    },

    components: {
      Loading, Paginator,
    },
  };
</script>
