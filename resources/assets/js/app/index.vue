<template>
  <div id="tickr-app">
    <div v-if="$store.status.loading">Loading...</div>
    <router-view v-else></router-view>
  </div>
</template>

<script>
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
     * Fires when the app has been mounted.
     * We prepare the application here.
     */
    mounted () {
      this.getAccount();
    },

    methods: {
      getAccount () {
        UserService.getAccount()
        .then(() => {
            this.store.dispatch('ready');
        });
      }
    }
  };
</script>
