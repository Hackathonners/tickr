<template>
  <ul v-for="error in errors">
    <li>{{ error }}</li>
  </ul>
  <input type="text" v-model="event.title" placeholder="Title">
  <input type="text" v-model="event.description" placeholder="Description">
  <input type="text" v-model="event.place" placeholder="Place">

  <input type="text" v-datepicker="dates.start_date" class="js-datepicker-input">
  <input type="time" v-model="dates.start_time">

  <input type="text" v-datepicker="dates.end_date" class="js-datepicker-input">
  <input type="time" v-model="dates.end_time">
  <ul>
    <li v-for="registration in event.registration" track-by="$index">
      <div>
        <span type="text">{{ registration.type }}</span>
        <span type="number">{{ registration.price | price }}</span>
        <span type="number">{{ registration.fine | price }}</span>
        <button @click="removeRegistrationType($index)" v-show="this.canRemoveRegistrationType()">X</button>
      </div>
    </li>
    <li>
      <div>
        <input type="text" v-model="registrationType.type" placeholder="Name">
        <input type="number" v-model="registrationType.price" step="0.01" min="0,00" placeholder="0,00" number>
        <input type="number" v-model="registrationType.fine" step="0.01" min="0,00" placeholder="0,00" number>
      </div>
      <button @click="addRegistrationType" :disabled="!this.validRegistrationType()">Adicionar</button>
    </li>
  </ul>
  <button @click="save">Criar Evento</button>
</template>

<script>
  import moment from 'moment';
  import '../../directives/Datepicker';
  import '../../filters/Price';
  export default {
    data() {
      return {
        event: {
          title: '',
          description: '',
          place: '',
          start_at: '',
          end_at: '',
          registration: [
            {
              type: 'Normal',
              price: 0.00,
              fine: 0.00,
            },
          ],
        },
        registrationType: this.resetRegistrationType(),
        errors: [],
        dates: {
          format: 'YYYY-MM-DD HH:mm:ss',
          start_date: '',
          end_date: '',
          start_time: '',
          end_time: '',
        }
      }
    },
    methods: {
      save() {
        this.$http.post('events', this.event).then(response => {
          console.log(response);
        }).catch( response => {
          this.errors = JSON.parse(response.body);
        })
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
      canRemoveRegistrationType() {
        return this.event.registration.length > 1;
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
          price: 0.00.toFixed(2),
          fine: 0.00.toFixed(2),
        };
      },
    },
    watch: {
      'dates': {
        deep: true,
        handler: function(dates) {
          let format = 'YYYY-MM-DD HH:mm';

          if(dates.start_date && dates.start_time) {
            let date = `${dates.start_date} ${dates.start_time}`;
            this.event.start_at = moment(date, format).format(dates.format);
          }

          if(dates.end_date && dates.end_time) {
            let date = `${dates.end_date} ${dates.end_time}`;
            this.event.end_at = moment(date, format).format(dates.format);
          }
        }
      }
    },
  };
</script>
