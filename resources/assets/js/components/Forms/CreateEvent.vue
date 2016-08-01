<template>
  <ul v-for="error in errors">
    <li>{{ error }}</li>
  </ul>
  <input type="text" name="title" v-model="event.title" placeholder="Title">
  <input type="text" name="description" v-model="event.description" placeholder="Description">
  <input type="text" name="place" v-model="event.place" placeholder="Place">
  <input type="datetime" name="start_at" v-model="event.start_at">
  <input type="datetime" name="end_at" v-model="event.end_at">
  <ul>
    <li v-for="registration in event.registration" track-by="$index">
      <div>
        <span type="text">{{ registration.type }}</span>
        <span type="number">{{ registration.price | currency '€' }}</span>
        <span type="number">{{ registration.fine | currency '€' }}</span>
      </div>
    </li>
    <li>
      <div>
        <input type="text" v-model="registrationType.type" placeholder="Name">
        <input type="number" v-model="registrationType.price" step="0.01" min="0,00" placeholder="0,00">
        <input type="number" v-model="registrationType.fine" step="0.01" min="0,00" placeholder="0,00">
      </div>
      <button @click="addRegistrationType" :disabled="!validRegistrationType">Adicionar</button>
    </li>
  </ul>
  <button @click="postEvent">Criar Evento</button>
</template>

<script>
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
              price: 0.00.toFixed(2),
              fine: 0.00.toFixed(2),
            },
          ],
        },
        registrationType: this.resetRegistrationType(),
        errors: [],
      }
    },
    methods: {
      postEvent () {
        this.$http.post('events', this.event).then(response => {
          console.log(response);
        }).catch( response => {
          this.errors = JSON.parse(response.body);
        })
      },
      addRegistrationType() {
        this.event.registration.push(this.registrationType);
        this.resetRegistrationType();
      },
      resetRegistrationType() {
        return this.registrationType = {
          type: '',
          price: 0.00.toFixed(2),
          fine: 0.00.toFixed(2),
        };
      },
    },
    computed: {
      validRegistrationType() {
        let type = this.registrationType.type.trim();
        let price = parseFloat(this.registrationType.price);
        let fine = parseFloat(this.registrationType.fine);

        if(type && !isNaN(price) && !isNaN(fine)) {
          return true;
        }

        return false;
      },
    },
  };
</script>
