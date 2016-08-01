<template>
  <div>{{ event.title }}</div>
  <pre>{{ event | json }}</pre>
</template>

<script>
  export default {
    data() {
      return {
        event: {}
      }
    },
    ready () {
      this.getEvent(this.$route.params.id)
    },
    methods: {
      getEvent (id) {
        this.$http.get('events/' + id).then(response => {
          this.$set('event', response.json().data)
        })
      },
    }
  };
</script>
