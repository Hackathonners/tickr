<template>
  <div class="row">
    <div class="col-md-12">
      <h3 class="page-title">{{ event.title }}</h3>
      <div class="text-muted">{{ event.place }}</div>
      <div class="text-muted">{{ event.start_at | date_range event.end_at }}</div>
    </div>
  </div>

  <!-- <pre>{{ event | json }}</pre> -->
  <!-- <create-registration :registrations.sync="registrations" :event="event" :registration-types="event.registration_types.data"></create-registration> -->
</template>

<script>
  import moment from 'moment';
  import CreateRegistration from './Forms/CreateRegistration.vue';
  import '../filters/Date';
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
