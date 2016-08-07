<template>
  <div>{{ event.title }}</div>
  {{ event |json }}
  <create-registration :registrations.sync="registrations" :event="event" :registration-types="event.registration_types.data"></create-registration>
</template>

<script>
  import CreateRegistration from './Forms/CreateRegistration.vue';
  export default {
    data() {
      return {
        // Event data
        event: {},

        // Registrations of this event
        registrations: [],
      }
    },
    ready () {
      this.getEvent(this.$route.params.id)
    },
    methods: {
      getEvent (id) {
        this.$http.get('events/' + id).then(response => {
          this.$set('event', response.json().data)
        }).catch(response => {
          this.$router.replace('/404');
        });
      },
    },
    components: {
      CreateRegistration,
    },
  };
</script>
