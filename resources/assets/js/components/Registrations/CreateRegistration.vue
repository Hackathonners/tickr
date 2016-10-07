<template>
  <div v-show="$loadingRouteData" class="row page-header">
    <loading :loading="$loadingRouteData" message="A carregar evento..."></loading>
  </div>

  <div v-show="!$loadingRouteData" class="page-content">
    <div class="row page-header">
      <div class="col-md-12">
        <span class="page-title">Nova inscrição em {{ event.title }}</span>
      </div>
    </div>

    <!-- Participants data -->
    <div class="fieldset">
      <span class="label label-primary step">1</span>
      <span class="title">Dados do participante</span>
    </div>

    <!-- Participant email -->
    <div :class="['form-group', error.messages['email'] ? 'has-error' : '']">
      <label for="registration-email">E-mail do participante</label>
      <div :class="{ 'input-group': loadingUser }">
        <input type="email" class="form-control" id="registration-email" v-model="registration.email">
        <span v-if="loadingUser" class="input-group-addon"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> A verificar</span>
      </div>
      <span v-if="error.messages['email']" class="text-danger small">{{ error.messages['email'] }}</span>
      <span v-else class="help-block small"><strong>Nota:</strong> O bilhete do evento será enviado para este e-mail.</span>
    </div>



    <div :class="['form-group', error.messages['name'] ? 'has-error' : '']">
      <label for="registration-name">Nome do participante</label>
      <input type="text" class="form-control" id="registration-name" disabled v-model="registration.name">
      <span v-if="error.messages['name']" class="text-danger small">{{ error.messages['name'] }}</span>
    </div>

    <div :class="['form-group', error.messages['notes'] ? 'has-error' : '']">
      <label for="registration-notes">Outras informações (opcional)</label>
      <textarea class="form-control" id="registration-notes" disabled v-model="registration.notes" rows="5"></textarea>
      <span v-if="error.messages['notes']" class="text-danger small">{{ error.messages['notes'] }}</span>
    </div>

    <!-- Registration Type details -->
    <div class="fieldset">
      <span class="label label-primary step">2</span>
      <span class="title">Bilhete do participante</span>
    </div>

    <div :class="['form-group', error.messages['registration_type'] ? 'has-error' : '']">
      <label for="registration-type">Tipo de bilhete</label>
      <select class="form-control" v-model="registration.registration_type">
        <option disabled selected value="">Selecionar o tipo de bilhete</option>
        <option v-for="registrationType in event.registration_types.data" :value="registrationType.id">
          {{ registrationType.type }}
        </option>
      </select>
      <span v-if="error.messages['registration_type']" class="text-danger small">{{ error.messages['registration_type'] }}</span>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" value="1" v-model="registration.fined" :checked="registration.fined"> Adicionar valor de multa ao preço do bilhete.</label>
    </div>

    <div class="fieldset">
      <span class="label label-primary step">3</span>
      <span class="title">Bom trabalho, está quase.</span>
    </div>

    <table v-show="registration.registration_type" class="table">
      <thead>
        <th>Descritivo</th>
        <th>Valor</th>
      </thead>
      <tbody>
        <tr>
          <td class="col-md-4">
            Bilhete {{ getRegistrationTypeData(registration.registration_type, 'type') }}
          </td>
          <td class="col-md-2 ">
            {{ getRegistrationTypeData(registration.registration_type, 'price') | price }}
          </td>
        </tr>
        <tr v-show="registration.fined">
          <td class="col-md-4">
            Multa
          </td>
          <td class="col-md-2 ">
            {{ getRegistrationTypeData(registration.registration_type, 'fine') | price }}
          </td>
        </tr>
      </tbody>
      <tfoot>
        <th>Total a pagar</th>
        <th>{{ getPaidValue() | price }}</th>
      </tfoof>
    </table>

    <submit-button :loading="loading" :callback="save" message="A inscrever...">Inscrever</submit-button>
  </div>
</template>

<script>
import moment from 'moment'
import Errors from '../Shared/Errors.vue'
import Loading from '../Shared/Loading.vue'
import EventService from '../../services/EventService.js'
import RegistrationService from '../../services/RegistrationService.js'
import UserService from '../../services/UserService.js'
import SubmitButton from '../Shared/SubmitButton.vue'
import { NotificationStore } from '../../stores/NotificationStore.js'
import '../../filters/Price'

export default {
  data () {
    return {
      // Results data
      event: {
        name: '',
        registration_types: {
          data: []
        }
      },

      // Form data
      registration: {},

      // Component status
      error: this.resetErrors(),
      loading: false,
      loadingUser: false,
      userTimeout: null
    }
  },
  created () {
    this.resetRegistrationState()
    this.loadEvent()
  },
  methods: {
    loadEvent () {
      this.$loadingRouteData = true
      EventService.get(this.$route.params.id, true).then(event => {
        this.$set('event', event.data)
        this.$loadingRouteData = false
      }).catch(response => {
        // Handle
      })
    },
    resetRegistrationState () {
      this.registration = {
        name: null,
        email: null,
        fined: moment().isBetween(this.event.start_at, this.event.end_at, 'day'),
        registration_type: '',
        notes: null
      }

      return this.registration
    },
    save () {
      this.resetErrors()
      this.loading = true
      RegistrationService.store(this.event.id, this.registration).then(registration => {
        NotificationStore.setNotification({
          text: 'A inscrição foi adicionada com sucesso.',
          type: 'success'
        })
        this.resetRegistrationState()
      }).catch(response => {
        window.scrollTo(0, 0) // Scroll to top, to see errors
        if (response.status === 422) {
          this.error = JSON.parse(response.body).error
        }
      }).then(() => {
        this.loading = false
        window.scrollTo(0, 0)
      })
    },
    getRegistrationTypeData (registrationTypeId, field) {
      const registrationType = this.event.registration_types.data.find(r => r.id === registrationTypeId)
      return registrationType && field in registrationType ? registrationType[field] : 0
    },
    getPaidValue () {
      const type = this.registration.registration_type

      if (typeof type === 'undefined' || !type) {
        return 0
      }

      const registrationType = this.event.registration_types.data.find(r => r.id === type)
      let value = registrationType.price
      value += this.registration.fined ? registrationType.fine : 0

      return value
    },
    resetErrors () {
      this.error = {
        messages: []
      }

      return this.error
    },
    findUser () {
      console.log('searching user...')
      UserService.find(this.registration.email).then((response) => {
        console.log(response)
      })
    }
  },
  watch: {
    'registration.email': function (newValue, oldValue) {
      clearTimeout(this.userTimeout)
      if (newValue) {
        this.userTimeout = setTimeout(this.findUser, 1000)
      }
    }
  },
  components: {
    Loading, Errors, SubmitButton
  }
}
</script>
