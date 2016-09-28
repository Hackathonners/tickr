<template>
  <div @click="triggerClose(notification)" class="alert" :class="['notification', 'alert', notification.type ? 'alert-' + notification.type : 'alert-info']" transition="fade">
    {{notification.text}}
  </div>
</template>
<script>
export default {
  props: ['notification'],
  data: function () {
    return {
      timer: null
    }
  },
  ready: function () {
    const timeout = this.notification.hasOwnProperty('timeout') ? this.notification.timeout : true
    if (timeout) {
      const delay = this.notification.delay || 5000
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
.alert-toast {
  cursor: pointer;
  display: table;
  margin: 0 auto;
}

.fade-transition {
  transition: all .3s ease;
}
/* .fade-enter defines the starting state for entering */
/* .fade-leave defines the ending state for leaving */
.fade-enter, .fade-leave {
  opacity: 0;
}
</style>
