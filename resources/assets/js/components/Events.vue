<template>
  <div class="row">
    <div class="col-md-6">
      <h3 class="page-title">Eventos</h3>
    </div>
    <div class="col-md-6">
      <a class="page-action btn btn-primary pull-right" v-link="{ name: 'events.create' }">Novo evento</a>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-tabs">
        <li :class="{ 'active': tabActive }"><a href="#" @click.prevent="getActiveEvents()">Eventos ativos</a></li>
        <li :class="{ 'active': tabOld }"><a href="#" @click.prevent="getOldEvents()">Eventos passados</a></li>
        <li :class="{ 'active': tabCanceled }"><a href="#" @click.prevent="getCanceledEvents()">Cancelados</a></li>
      </ul>
      <table class="table table--events">
        <tbody>
          <tr v-show="!$loadingRouteData" v-for="event in events">
            <td class="col-md-7 event-info">
              <div class="event-info__name">
                <a v-link="{ name: 'events.show', params: { id: event.id }}">{{ event.title }}</a>
              </div>
              <div class="event-info__place text-muted">
                {{ event.place }} · Criado {{ event.created_at | human_diff }} · Editado {{ event.updated_at | human_diff }}
              </div>
            </td>
            <td class="col-md-3 event-info">
              <div v-if="isRunning(event)" class="event-info__status event-info__status--running">
                <span class="label label-primary label-outline">A decorrer ({{ event.end_at | human_diff true }} remaining)</span>
              </div>
              <div v-else class="event-info__status event-info__status--waiting">
                <span class="status__title">Starts {{ event.start_at | human_diff }}</span>
                <div class="status__details text-muted">
                  {{ event.start_at | date 'dddd' }}, {{ event.start_at | date 'D MMMM YYYY' }}
                </div>
              </div>
            </td>
            <td class="col-md-2 event-info">
              <span v-if="event.registrations">{{ event.registrations}}</span>
              <span v-else>No tickets sold</span>
            </td>
          </tr>
        </tbody>
      </table>
      <pulse-loader v-show="$loadingRouteData"></pulse-loader>
    </div>
  </div>
</template>

<script>
  import { PulseLoader } from 'vue-spinner/dist/vue-spinner.min'
  import moment from 'moment';
  import '../filters/Date';
  export default {
    data() {
      return {
        visibility: '',
        format: 'YYYY-MM-DD HH:mm:ss',
        events: [],
      }
    },
    methods: {
      getActiveEvents () {
        if(this.tabActive)
          return;

        this.$set('visibility', 'active')
        this.$loadingRouteData = true;
        return this.$http.get('events').then(response => {
          let events = response.json().data;
          this.$set('events', events)
          this.$loadingRouteData = false;
          return events;
        })
      },
      getOldEvents () {
        if(this.tabOld)
          return;

        this.$set('visibility', 'old')
        this.$loadingRouteData = true;
        return this.$http.get('events/1').then(response => {
          this.$set('events', [response.json().data])
          this.$loadingRouteData = false;
        })
      },
      getCanceledEvents () {
        if(this.tabCanceled)
          return;

        this.$set('visibility', 'canceled')
        this.$loadingRouteData = true;
        return this.$http.get('events/1').then(response => {
          this.$set('events', [response.json().data])
          this.$loadingRouteData = false;
        })
      },
      isRunning(event) {
        return moment().isBetween(event.start_at, event.end_at);
      }
    },
    computed: {
      tabActive() {
        return this.visibility == 'active'
      },
      tabOld() {
        return this.visibility == 'old'
      },
      tabCanceled() {
        return this.visibility == 'canceled'
      },
    },
    route: {
      data: function () {
        return {
          events: this.getActiveEvents()
        }
      }
    },
    components: {
      PulseLoader,
    }
  };
</script>
