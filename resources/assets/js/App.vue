<template>
  <main>
    <navbar></navbar>
    <div class="container">
      <router-view></router-view>
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

export default {
  replace: false,
  data () {
    return {}
  },
  components: {
    Navbar
  }
}
</script>
