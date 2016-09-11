<template>
  <div class="row page-header">
    <div class="col-md-8">
      <span class="page-title">Eventos</span>
    </div>
    <div class="col-md-4">
      <a class="page-action btn btn-primary pull-right" v-link="{ name: 'events.create' }">Novo evento</a>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-tabs">
        <li :class="{ 'active': showActive }"><a href="#" @click.prevent="showActiveEvents()">Eventos ativos</a></li>
        <li :class="{ 'active': showPast }"><a href="#" @click.prevent="showPastEvents()">Eventos passados</a></li>
        <li :class="{ 'active': showCanceled }"><a href="#" @click.prevent="showCanceledEvents()">Cancelados</a></li>
      </ul>
      <table class="table table--noheading table--events">
        <tbody>
          <tr v-show="!$loadingRouteData" v-for="event in events">
            <td class="col-md-6 event-info">
              <!-- Event details -->
              <div class="event-info__name">
                <a v-link="{ name: 'events.show', params: { id: event.id }}">{{ event.title }}</a>
              </div>
              <div class="event-info__place text-muted">
                {{ event.place }} · Criado {{ event.created_at | human_diff }}
                <span v-show="isEdited(event)"> · Editado {{ event.updated_at | human_diff }}</span>
              </div>
            </td>
            <td class="col-md-3 event-info">
              <!-- Event status -->
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
            <td class="col-md-3 event-info">
              <!-- Event ticket status -->
              <span v-if="event.registrations">{{ event.registrations }}</span>
              <span v-else>Sem bilhetes vendidos</span>
            </td>
          </tr>
        </tbody>
      </table>
      <loading :loading="$loadingRouteData"></loading>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  import Loading from './Util/Loading.vue';
  import EventService from '../services/EventService.js';
  import '../filters/Date';

  export default {
    data() {
      return {
        visibility: '',
        format: 'YYYY-MM-DD HH:mm:ss',
        events: [],
      }
    },
    ready() {
      this.showActiveEvents();
    },
    methods: {
      showActiveEvents () {
        if(this.showActive) return;

        this.$set('visibility', 'active');
        this.$loadingRouteData = true;
        EventService.getActive().then(events => {
          this.$set('events', events);
          this.$loadingRouteData = false;
        })
      },
      showPastEvents () {
        if(this.showPast) return;

        this.$set('visibility', 'past')
        this.$loadingRouteData = true;
        EventService.getPast().then(events => {
          this.$set('events', events)
          this.$loadingRouteData = false;
        })
      },
      showCanceledEvents () {
        if(this.showCanceled) return;

        this.$set('visibility', 'canceled')
        this.$loadingRouteData = true;
        EventService.getActive().then(events => {
          this.$set('events', events)
          this.$loadingRouteData = false;
        })
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
      showActive() {
        return this.visibility == 'active'
      },
      showPast() {
        return this.visibility == 'past'
      },
      showCanceled() {
        return this.visibility == 'canceled'
      },
    },
    components: {
      Loading
    }
  };
</script>
