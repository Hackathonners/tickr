<template>
  <table class="table">
    <thead>
      <th class="col-md-4">Nome</th>
      <th class="col-md-2">Tipo de bilhete</th>
      <th class="col-md-1">Data</th>
      <th class="col-md-1 text-center">Ativado</th>
    </thead>
    <tbody>
      <!-- Loading results -->
      <tr v-show="loading">
        <td class="event-info text-center" colspan="4">
          <loading :loading="loading" message="A carregar inscrições..."></loading>
        </td>
      </tr>

      <!-- No results -->
      <tr class="no-results" v-show="registrations.length == 0 && !loading">
        <td class="event-info text-center" colspan="4">
          Não existem inscrições neste evento, <a class="text-primary" v-link="action">crie uma inscrição</a>.
        </td>
      </tr>

      <!-- Results -->
      <tr v-show="!loading" v-for="registration in registrations">
        <td>
          {{ registration.user.data.name }}
        </td>
        <td>
          {{ registration.registration_type.data.type }}
          <span v-show="registration.fined" class="label label-warning label-outline">Multa</span>
        </td>
        <td>
          {{ registration.created_at | human_diff }}
        </td>
        <td class="text-center">
          <i :class="['fa', registration.activated ? 'fa-check text-primary' : 'fa-close text-muted']" aria-hidden="true"></i>
        </td>
      </tr>
    </tbody>
  </table>
  <paginator v-show="paginate && !loading" :pagination.sync="pagination" :callback="loadRegistrations"></paginator>
</template>
<script>
import Loading from '../../Shared/Loading.vue'
import Paginator from '../../Shared/Paginator.vue'
import RegistrationService from '../../../services/RegistrationService.js'

export default {
  props: {
    eventId: {
      required: true
    },
    paginate: {
      required: false,
      default: true
    },
    limit: {
      required: false,
      type: Number,
      default: 20
    },
    action: {
      required: true,
      type: Object
    }
  },

  data () {
    return {
      // Results data
      registrations: [],

      // Component status
      pagination: {},
      loading: false
    }
  },

  ready () {
    this.loadRegistrations(this.$route.query.page)
  },

  methods: {
    loadRegistrations (page = 1) {
      this.loading = true
      RegistrationService.list(this.eventId, page, this.limit).then(registrations => {
        this.$set('registrations', registrations.data)
        this.$set('pagination', registrations.meta.pagination)
        this.loading = false
      })
    }
  },

  watch: {
    '$route.query.page': function (newValue, oldValue) {
      this.loadRegistrations(newValue)
    }
  },

  components: {
    Loading, Paginator
  }
}
</script>
