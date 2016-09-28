<template>
  <main>
    <navbar></navbar>
    <div class="container">
      <router-view></router-view>
    </div>
    <div class="notification-wrapper">
      <notification v-for="notification in notifications" :notification="notification" @close-notification="removeNotification"></notification>
    </div>
  </main>
  <footer class="sticky-footer">
    <div class="container">
      <!-- Sticky footer -->
    </div>
  </footer>
</template>

<script>
import Navbar from './components/Shared/Navbar.vue'
import Notification from './components/Shared/Notification.vue'
import { NotificationStore } from './stores/NotificationStore.js'

export default {
  replace: false,
  data () {
    return {
      notifications: NotificationStore.state
    }
  },
  methods: {
    removeNotification () {
      NotificationStore.removeNotification()
    }
  },
  components: {
    Navbar, Notification
  }
}
</script>
