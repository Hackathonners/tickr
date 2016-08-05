<template>
  <ul v-for="error in errors">
    <li>{{ error }}</li>
  </ul>
  {{ registrationTypes | json }}
  <button @click="save">Inscrever</button>
</template>

<script>
  import '../../filters/Price';
  export default {
    props: ['event-id', 'registration-types'],
    data() {
      return {
        registration: this.resetRegistrationState(),
      }
    },
    ready() {
      //this.getAndNewRegistration();
    },
    methods: {
      resetRegistrationState() {
        return this.registration = {
          email: 'fntneves@gmail.com',
          fined: false,
          registration_type: 1,
        };
      },
      save() {
        console.log(JSON.stringify(this.registration));
        this.$http.post('events/' + this.eventId + '/registrations', this.registration).then(response => {
          this.registrations.push(this.registration);
          this.resetRegistrationState();
          console.log(response);

        }).catch( response => {
          this.errors = JSON.parse(response.body);
        })
      },
    },
  };
</script>
