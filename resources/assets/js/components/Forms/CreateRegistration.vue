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

    <!-- Form Errors -->
    <errors :error="error"></errors>

    <!-- New Registration Form -->
    <!-- Registration details -->
    <div class="fieldset">
      <span class="label label-primary step">1</span>
      <span class="title">Dados do participante</span>
    </div>

    <div class="form-group">
      <label for="registration-email">E-mail do participante</label>
      <input type="email" class="form-control" id="registration-email" v-model="registration.email">
      <span class="help-block small"><strong>Nota:</strong> O bilhete do evento será enviado para este e-mail.</span>
    </div>
    <div class="form-group">
      <label for="registration-name">Nome do participante</label>
      <input type="text" class="form-control" id="registration-name" v-model="registration.name">
    </div>
    <div class="form-group">
      <label for="registration-notes">Outras informações</label>
      <textarea class="form-control" id="registration-notes" v-model="registration.notes" rows="5"></textarea>
    </div>

    <!-- Registration Type details -->
    <div class="fieldset">
      <span class="label label-primary step">2</span>
      <span class="title">Bilhete do participante</span>
    </div>

    <div class="form-group">
      <label for="registration-type">Tipo de bilhete</label>
      <select class="form-control" v-model="registration.registration_type">
        <option disabled selected value="">Selecionar o tipo de bilhete</option>
        <option v-for="registrationType in event.registration_types.data" :value="registrationType.id">
          {{ registrationType.type }}
        </option>
      </select>
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

    <div class="form-group">
      <button class="btn btn-primary" @click.prevent="save">Inscrever</button>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  import Errors from '../Layout/Errors.vue';
  import EventService from '../../services/EventService.js';
  import RegistrationService from '../../services/RegistrationService.js';
  import Loading from '../Util/Loading.vue';
  import '../../filters/Price';

  export default {
    data() {
      return {
        // Results data
        event: {
          name: '',
          registration_types: {
            data: [],
          },
        },

        // Form data
        registration: {},

        // Component status
        error: null,
      }
    },
    ready () {
      this.resetRegistrationState();
      this.loadEvent();
    },
    methods: {
      loadEvent() {
        this.$loadingRouteData = true;
        EventService.get(this.$route.params.id, true).then(event => {
          this.$set('event', event.data);
          this.$loadingRouteData = false;
        });
      },
      resetRegistrationState() {
        return this.registration = {
          name: null,
          email: null,
          fined: moment().isBetween(this.event.start_at, this.event.end_at, 'day'),
          registration_type: '',
          notes: null,
        };
      },
      save() {
        RegistrationService.store(this.event.id, this.registration).then(registration => {
          // Success message
          this.resetRegistrationState();
        }).catch( response => {
          this.error = JSON.parse(response.body).error;
        });
      },
      getRegistrationTypeData (registrationTypeId, field) {
        let registrationType = this.event.registration_types.data.find(r => r.id == registrationTypeId);
        return registrationType && field in registrationType ? registrationType[field] : 0;
      },
      getPaidValue() {
        let type = this.registration.registration_type;

        if(typeof type === 'undefined' || !type){
          return 0;
        }

        let registrationType = this.event.registration_types.data.find(r => r.id == type);
        let value = registrationType.price;
        value += this.registration.fined ? registrationType.fine : 0;

        return value;
      }
    },
    components: {
      Loading, Errors
    },
  };
</script>
