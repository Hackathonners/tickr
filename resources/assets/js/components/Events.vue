<template>
  <ul>
    <li v-for="event in events">
      <div>
        <a v-link="{ name: 'events.show', params: { id: event.id }}">{{ event.title }}</a>
        <pre>{{ event | json }}</pre>
      </div>
    </li>
  </ul>
</template>

<script>
  export default {
    data() {
      return {
        events: []
      }
    },
    ready () {
      this.getEvents()
    },
    methods: {
      getEvents () {
        this.$http.get('events').then(response => {
          this.$set('events', response.json().data)
        })
      },
    }
  };
</script>
