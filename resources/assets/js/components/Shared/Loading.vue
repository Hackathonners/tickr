<template>
    <div v-show="loading" class="js-loading text-center">
        <pulse-loader></pulse-loader>
        <span class="text-muted small">{{ message ? message : 'A carregar...' }}</span>
    </div>
</template>

<script>
import { PulseLoader } from 'vue-spinner/dist/vue-spinner.min'

export default {
  props: ['loading', 'message'],
  components: {
    PulseLoader
  }
}
</script>
