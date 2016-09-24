<template>
    <main>
        <navbar></navbar>
        <div class="container">
            <div class="notifications-wrapper">
                <notification v-for="notification in notifications" :notification="notification" @close-notification="removeNotification" transition="fade">
                </notification>
            </div>
            <router-view></router-view>
        </div>
    </main>

    <footer class="sticky-footer">
        <div class="container">
            ola sticky footer
        </div>
    </footer>
</template>
<script>
import Navbar from './components/Shared/Navbar.vue'
import Notification from './components/Shared/Notification.vue'
import { NotificationStore } from './stores/NotificationStore.js'

export default {
  components: { Navbar, Notification },
  replace: false,
  data () {
    return {
      notifications: NotificationStore.state
    }
  },
  methods: {
    removeNotification: function (notification) {
      NotificationStore.removeNotification(notification)
    }
  }
}
</script>
<style>
  .notifications-wrapper {
    z-index: 10;
    position: fixed;
    top: -3px;
    left: 0;
    right: 0;
  }
</style>
