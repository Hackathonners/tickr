<template>
  <div v-show="$loadingRouteData" class="row page-header">
    <loading :loading="$loadingRouteData" message="A carregar..."></loading>
  </div>

  <div v-show="!$loadingRouteData" class="page-content">
    <div class="row page-header">
      <div class="col-xs-8">
        <span class="page-title">Inscrições em {{ event.title }}</span>
        <div class="text-muted">{{ event.start_at | date_range event.end_at }}</div>
        <div class="text-muted">{{ event.place }}</div>
      </div>
      <div class="col-xs-4">
        <a class="page-action btn btn-primary pull-right" v-link="{ name: 'registrations.create', params: { id: event.id } }">Nova inscrição</a>
      </div>
    </div>
    <registrations-list :registrations="registrations"></registrations-list>
    <paginator v-show="!$loadingRouteData" :pagination.sync="pagination" :callback="loadRegistrations"></paginator>
  </div>
</template>

<script>
  import Loading from './Shared/Loading.vue';
  import Paginator from './Shared/Paginator.vue';
  import RegistrationsList from './Shared/Registrations/RegistrationsList.vue';
  import EventService from '../services/EventService.js';
  import RegistrationService from '../services/RegistrationService.js';

  export default {
    data() {
      return {
        // Results data
        event: {},
        registrations: [],

        // Component status
        pagination: {},
      };
    },
    ready () {
      this.loadEvent();
      this.loadRegistrations();
    },
    methods: {
      loadEvent() {
        this.$loadingRouteData = true;
        EventService.get(this.$route.params.id).then(event => {
          this.$set('event', event.data);
          this.$loadingRouteData = false;
        });
      },
      loadRegistrations(page = 1) {
        this.$loadingRouteData = true;
        RegistrationService.list(this.$route.params.id, page).then(registrations => {
          this.$set('registrations', registrations.data);
          this.$set('pagination', registrations.meta.pagination);
          this.$loadingRouteData = false;
        });
      }
    },
    components: {
      Loading, RegistrationsList, Paginator
    },
  };
</script>
