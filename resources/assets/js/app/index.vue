<template>
  <div id="tickr-app">
    <div v-if="loading">Loading...</div>
    <router-view v-else></router-view>
  </div>
</template>
<script>
  /* ============
   * Entry Point
   * ============
   *
   * The entry point of the application
   */

  import store from './store';
  import UserService from './services/account'
  import { router } from './../bootstrap';

  export default {
    data () {
      return {
        loading: true,
      }
    },

    /**
     * The Vuex store
     */
    store,

    /**
     * The router
     */
    router,

    /**
     * Fires when the app has been mounted
     */
    mounted () {
      this.getAccount();
    },

    methods: {
      getAccount () {
        UserService.getAccount()
          .then(() => {
            this.loading = false;
          });
      }
    }
  };
</script>
