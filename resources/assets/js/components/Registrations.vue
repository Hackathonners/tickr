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
    <registrations-list :event-id.sync="$route.params.id" :action="{ name: 'registrations.create', params: { id: event.id } }"></registrations-list>
  </div>
</template>

<script>
import Loading from './Shared/Loading.vue'
import RegistrationsList from './Shared/Registrations/RegistrationsList.vue'
import EventService from '../services/EventService.js'
import { NotificationStore } from '../stores/NotificationStore.js'

export default {
  data () {
    return {
      // Results data
      event: {}
    }
  },

  ready () {
    this.loadEvent()
  },

  methods: {
    loadEvent () {
      this.$loadingRouteData = true
      EventService.get(this.$route.params.id).then(event => {
        this.$set('event', event.data)
        this.$loadingRouteData = false
      }).catch(response => {
        switch (response.status) {
          case 404:
            NotificationStore.addNotification({
              text: 'O evento solicitado já não existe.',
              type: 'danger',
              timeout: true
            })
            this.$router.replace({ name: 'events' })
            break
        }
      })
    }
  },

  components: {
    Loading, RegistrationsList
  }
}
</script>
