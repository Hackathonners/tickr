<template>
  <div v-show="$loadingRouteData" class="row page-header">
    <loading :loading="$loadingRouteData" message="A carregar evento..."></loading>
  </div>

  <div v-show="!$loadingRouteData" class="page-content">
    <div class="row page-header">
      <div class="col-md-8">
        <span class="page-title">{{ event.title }}</span>
        <div class="text-muted">{{ event.start_at | date_range event.end_at }}</div>
        <div class="text-muted">{{ event.place }}</div>
      </div>
      <div class="col-md-4">
        <a class="page-action btn btn-primary pull-right" v-link="{ name: 'registrations.create', params: { id: event.id } }">Nova inscrição</a>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Receita total</h4>
            <h2 class="text-primary">{{ event.stats.income | currency '€' }}</h2>
          </div>
          <ul class="list-group">
            <li class="list-group-item">
              <span class="pull-right">{{ event.stats.registrations }}</span> Total de bilhetes vendidos
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Taxa de participação</h4>
            <h2 class="text-primary">{{ (event.stats.participations / event.stats.registrations) | ratio }}</h2>
          </div>
          <ul class="list-group">
            <li class="list-group-item">
              <span class="pull-right">{{ event.stats.participations }}<span class="text-muted"> / {{ event.stats.registrations }} inscritos</span></span>
              Total de participantes
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Resumo dos bilhetes</h4>
            <table class="table">
              <thead>
                <th>Tipo de bilhete</th>
                <th class="text-center">Vendas</th>
                <th>Receita</th>
                <th>Taxa de participação</th>
              </thead>
              <tbody>
                <tr v-for="registrationType in event.registration_types.data">
                  <td class="col-md-4">
                    {{ registrationType.type }}
                  </td>
                  <td class="col-md-2 text-center">
                    {{ getRegistrationTypeStats(registrationType.id, 'registrations') }}
                  </td>
                  <td class="col-md-2">
                    {{ getRegistrationTypeStats(registrationType.id, 'income') | currency '€' }}
                  </td>
                  <td class="col-md-3">
                    {{ (getRegistrationTypeStats(registrationType.id, 'participations') / getRegistrationTypeStats(registrationType.id, 'registrations')) | ratio }}

                    <span class="text-muted">({{ getRegistrationTypeStats(registrationType.id, 'participations')}} participantes)</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Loading from './Util/Loading.vue';
  import CreateRegistration from './Forms/CreateRegistration.vue';
  import EventService from '../services/EventService.js';
  import '../filters/Date';
  import '../filters/Ratio';
  export default {
    data() {
      return {
        // Event data
        event: {
          stats: {
            income: 0,
            participations: 0,
            registrations: 0,
            registration_types: [],
          },
          registration_types: {
            data: []
          }
        },
        // Registrations of this event
        registrations: [],
      }
    },
    ready () {
      this.$loadingRouteData = true;
      EventService.get(this.$route.params.id, true).then(event => {
        this.$set('event', event);
        this.$loadingRouteData = false;
      })
    },
    methods: {
      getRegistrationTypeStats (registrationTypeId, statsField) {
        let registrationTypeStats = this.event.stats.registration_types.find(r => r.id == registrationTypeId);
        return registrationTypeStats && statsField in registrationTypeStats ? registrationTypeStats[statsField] : 0;
      },
    },
    components: {
      CreateRegistration, Loading
    },
  };
</script>
