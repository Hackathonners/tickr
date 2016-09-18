<template>
  <div class="row page-header">
    <div class="col-md-12">
      <span class="page-title">Novo evento</span>
    </div>
  </div>

  <!-- Form Errors -->
  <errors :error="error"></errors>

  <!-- New Event Form -->
  <!-- Event details -->
  <div class="fieldset">
    <span class="label label-primary step">1</span>
    <span class="title">Detalhes do evento</span>
  </div>

  <div class="form-group">
    <label for="event-title">Nome do evento</label>
    <input type="text" class="form-control" id="event-title" v-model="event.title">
  </div>
  <div class="form-group">
    <label for="event-description">Descrição do evento</label>
    <textarea type="text" class="form-control" id="event-description" v-model="event.description" rows="5"></textarea>
  </div>
  <div class="form-group">
    <label for="event-place">Local do evento</label>
    <input type="text" class="form-control" id="event-place" v-model="event.place">
  </div>

  <!-- Event dates -->
  <div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        <label>Data e hora de início</label>
        <div class="input-group">
          <input type="text" v-datepicker="dates.start_date" class="form-control js-datepicker-input">
          <span class="input-group-addon">às</span>
          <input type="time" v-model="dates.start_time" class="form-control">
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label>Data e hora de fim</label>
        <div class="input-group">
          <input type="text" v-datepicker="dates.end_date" class="form-control js-datepicker-input">
          <span class="input-group-addon">às</span>
          <input type="time" v-model="dates.end_time" class="form-control">
        </div>
      </div>
    </div>
  </div>

  <!-- Registration Types -->
  <div class="fieldset">
    <span class="label label-primary step">2</span>
    <span class="title">Tipos de bilhete</span>
  </div>

  <div class="row hidden-sm hidden-xs">
    <div class="col-sm-6">
      <label>Tipo de bilhete</label>
    </div>
    <div class="col-sm-3">
      <label>Preço</label>
    </div>
    <div class="col-sm-3">
      <label>Multa</label> <i class="fa fa-info-circle text-muted" data-toggle="tooltip" title="Aplicada quando o bilhete é adquirido no dia em que o evento inicia." aria-hidden="true"></i>
    </div>
  </div>

  <div class="row" v-for="registration in event.registration" track-by="$index">
    <div class="col-sm-6">
      <div class="form-group">
        <div class="input-group">
          <input type="text" v-model="registration.type" class="form-control" placeholder="Nome do bilhete">
          <div class="input-group-btn">
            <button :disabled="event.registration.length < 2" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="text-danger" @click.prevent="removeRegistrationType($index)" href="#">Remover</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-xs-6">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon">€</div>
          <input type="number" v-model="registration.price" min="0.00" step="0.01" class="form-control" placeholder="Preço" number>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-xs-6">
      <div class="input-group">
        <div class="input-group-addon">€</div>
        <input type="number" v-model="registration.fine" min="0.00" step="0.01" class="form-control" placeholder="Multa" number>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        <button class="btn btn-default" @click.prevent="addRegistrationType">Adicionar tipo</button>
      </div>
    </div>
  </div>

  <!-- Create event -->
  <div class="fieldset">
    <span class="label label-primary step">3</span>
    <span class="title">Bom trabalho, está quase.</span>
  </div>

  <div class="form-group">
    <button class="btn btn-primary" @click.prevent="save">Criar Evento</button>
  </div>
</template>

<script>
  import moment from 'moment';
  import Errors from './Shared/Errors.vue';
  import EventService from '../services/EventService.js';
  import '../directives/Datepicker';
  import '../filters/Price';

  export default {
    data() {
      return {
        // Form data
        event: {
          title: '',
          description: '',
          place: '',
          start_at: '',
          end_at: '',
          registration: [
            {
              type: 'Normal',
              price: '',
              fine: '',
            }
          ],
        },

        // Registration type data
        registrationType: this.resetRegistrationType(),

        // Component status
        error: null,
        dates: {
          format: 'YYYY-MM-DD HH:mm:ss',
          start_date: '',
          end_date: '',
          start_time: '',
          end_time: '',
        }
      };
    },
    methods: {
      save() {
        EventService.store(this.event).then(event => {
          // Success message
          alert("created");
        }).catch( response => {
          this.error = JSON.parse(response.body).error;
        });
      },
      validRegistrationType() {
        let type = this.registrationType.type.trim();
        let price = parseFloat(this.registrationType.price);
        let fine = parseFloat(this.registrationType.fine);

        if(type && !isNaN(price) && !isNaN(fine)) {
          return true;
        }

        return false;
      },
      addRegistrationType() {
        this.event.registration.push(this.registrationType);
        this.resetRegistrationType();
      },
      removeRegistrationType(index) {
        this.event.registration.splice(index, 1);
      },
      resetRegistrationType() {
        return this.registrationType = {
          type: '',
          price: '',
          fine: '',
        };
      },
    },
    watch: {
      'dates': {
        deep: true,
        handler: function(dates) {
          let format = 'YYYY-MM-DD HH:mm';
          let lastStartDate = this.event.start_at;
          let lastEndDate = this.event.end_at;

          this.event.start_at = this.event.end_at = '';

          if(dates.start_date && dates.start_time) {
            let date = moment(`${dates.start_date} ${dates.start_time}`, format);
            this.event.start_at = date.isValid() ? date.format(dates.format) : lastStartDate;
          }

          if(dates.end_date && dates.end_time) {
            let date = moment(`${dates.end_date} ${dates.end_time}`, format);
            this.event.end_at = date.isValid() ? date.format(dates.format) : lastEndDate;
          }
        },
      },
    },
    components: {
      Errors,
    },
  };
</script>
