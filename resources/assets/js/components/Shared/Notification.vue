<template>
  <div class="notification callout animated" :class="notification.type ? notification.type : 'secondary'" transition="fade">
    <button @click="triggerClose(notification)" class="close-button" aria-label="Close alert" type="button">
      <span aria-hidden="true">&times;</span>
    </button>
    <h5 v-if="notification.title">{{notification.title}}</h5>
    <p>
      {{notification.text}}
    </p>
  </div>
</template>
<script>
export default {
  props: ['notification'],
  data: function () {
    return { timer: null }
  },
  ready: function () {
    const timeout = this.notification.hasOwnProperty('timeout') ? this.notification.timeout : true
    if (timeout) {
      const delay = this.notification.delay || 10000
      this.timer = setTimeout(function () {
        this.triggerClose(this.notification)
      }.bind(this), delay)
    }
  },

  methods: {
    triggerClose: function (notification) {
      clearTimeout(this.timer)
      this.$dispatch('close-notification', notification)
    }
  }
}
</script>
<style>
.notifications {
  position: fixed;
  right: 10px;
  top: 10px;
  width: 350px;
  z-index: 1;
}
.notification p {
  margin-right: 20px;
}

/* always present */
.fade-transition {
  transition: all .5s ease;
  padding: 10px;
  background-color: #eee;
  overflow: hidden;
}
/* .fade-enter defines the starting state for entering */
/* .fade-leave defines the ending state for leaving */
.fade-enter, .fade-leave {
  opacity: 0;
}
</style>
