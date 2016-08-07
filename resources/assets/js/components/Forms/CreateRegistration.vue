<template>
  <ul v-for="error in errors">
    <li>{{ error }}</li>
  </ul>

  <p>
  <input type="text" v-model="registration.email">
  <input type="checkbox" value="1" v-model="registration.fined">
  <select v-model="registration.registration_type">
    <option v-for="registrationType in registrationTypes" :value="registrationType.id">
      {{ registrationType.type }}
    </option>
  </select>
  {{ registrationTypes | json }}
  </p>
  <button @click="save">Inscrever</button>
</template>

<script>
  import '../../filters/Price';
  export default {
    props: ['event', 'registration-types', 'registrations'],
    data() {
      return {
        registration: this.resetRegistrationState(),
        errors: [],
      }
    },
    methods: {
      resetRegistrationState() {
        return this.registration = {
          email: '',
          fined: false,
          registration_type: null,
        };
      },
      save() {
        this.$http.post('events/' + this.event.id + '/registrations', this.registration).then(response => {
          this.registrations.push(this.registration);
          this.resetRegistrationState();
        }).catch( response => {
          this.errors = JSON.parse(response.body);
        })
      },
    },
  };
</script>
