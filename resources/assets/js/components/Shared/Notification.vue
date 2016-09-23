<template>
  <div class="alert" :class="['alert', notification.type ? 'alert-' + notification.type : 'alert-info']" transition="fade">
    <button @click="triggerClose(notification)" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
.fade-transition {
  transition: all .3s ease;
}
/* .fade-enter defines the starting state for entering */
/* .fade-leave defines the ending state for leaving */
.fade-enter, .fade-leave {
  opacity: 0;
}
</style>
