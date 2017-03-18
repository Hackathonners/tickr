<template>
  <div class="app-container">
    <div v-if="!ready">Loading...</div>
    <router-view v-else></router-view>
  </div>
</template>

<script>
  import store from 'app/store';
  import UserService from 'app/services/account'
  import { router } from '../../bootstrap';

  export default {
    data () {
      return {
        ready: false,
      }
    },

    mounted () {
      this.getAccount();
    },

    methods: {
      getAccount () {
        UserService.getAccount()
        .then(() => {
            this.ready = true;
        });
      }
    }
  };
</script>
