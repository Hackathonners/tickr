<template>
  <div v-show="$loadingRouteData" class="row page-header">
    <loading :loading="$loadingRouteData" message="A carregar evento..."></loading>
  </div>

  <div v-show="!$loadingRouteData" class="page-content">
    <div class="row page-header">
      <div class="col-xs-8">
        <span class="page-title">{{ event.title }}</span>
        <div class="text-muted">{{ event.start_at | date_range event.end_at }}</div>
        <div class="text-muted">{{ event.place }}</div>
      </div>
      <div class="col-xs-4">
        <a class="page-action btn btn-primary pull-right" v-link="{ name: 'registrations.create', params: { id: event.id } }">Nova inscrição</a>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
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
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Taxa de participação</h4>
            <h2 :class="[ participationRatio > 0.5 ? 'text-primary' : 'text-muted' ]">{{ participationRatio | ratio }}</h2>
          </div>
          <ul class="list-group">
            <li class="list-group-item">
              <span class="pull-right">{{ event.stats.participations }}<span class="text-muted"> / <a class="text-muted" v-link="{ name: 'registrations', params: { id: event.id } }">{{ event.stats.registrations }} inscritos</a></span></span>
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
                <th>Preço</th>
                <th>Multa</th>
                <th>Vendas</th>
                <th>Receita</th>
                <th>Taxa de participação</th>
              </thead>
              <tbody>
                <tr v-for="registrationType in event.registration_types.data">
                  <td class="col-md-3">
                    {{ registrationType.type }}
                  </td>
                  <td class="col-md-1">
                    {{ registrationType.price | price }}
                  </td>
                  <td class="col-md-1">
                    {{ registrationType.fine | price }}
                  </td>
                  <td class="col-md-1">
                    {{ getRegistrationTypeStats(registrationType.id, 'registrations') }}
                  </td>
                  <td class="col-md-1">
                    {{ getRegistrationTypeStats(registrationType.id, 'income') | currency '€' }}
                  </td>
                  <td class="col-md-2">
                    {{ getRegistrationTypeParticipationRatio(registrationType.id) | ratio }}

                    <span class="text-muted">({{ getRegistrationTypeStats(registrationType.id, 'participations')}} participantes)</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Últimas inscrições <small class="pull-right"><a v-link="{ name: 'registrations', params: { id: event.id } }">Ver todas as inscrições</a></small></h4>
            <registrations-list :limit="10" :paginate="false" :event-id.sync="$route.params.id" :action="{ name: 'registrations.create', params: { id: event.id } }"></registrations-list>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Loading from './Shared/Loading.vue'
import RegistrationsList from './Shared/Registrations/RegistrationsList.vue'
import EventService from '../services/EventService.js'
import { NotificationStore } from '../stores/NotificationStore.js'
import '../filters/Date'
import '../filters/Ratio'

export default {
  data () {
    return {
      // Results data
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
      }
    }
  },
  ready () {
    this.loadEvent()
  },
  methods: {
    loadEvent () {
      this.$loadingRouteData = true
      EventService.get(this.$route.params.id, true).then(event => {
        this.$set('event', event.data)
        this.$loadingRouteData = false
      }).catch(response => {
        if (response.status === 404) {
          NotificationStore.addNotification({
            text: 'O evento solicitado já não existe.',
            type: 'danger',
            timeout: true
          })
          this.$router.replace({ name: 'events' })
        }
      })
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
    Loading, RegistrationsList
  }
}
</script>
