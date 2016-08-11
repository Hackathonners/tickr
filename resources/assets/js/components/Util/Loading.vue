<template>
    <div v-show="loading" class="js-loading text-center">
        <clip-loader></clip-loader>
        <span class="text-muted small">{{ message ? message : 'A carregar...' }}</span>
    </div>
</template>

<script>
  import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min'
  export default {
    props: ['loading', 'message'],
    components: {
        ClipLoader
    }
  }
</script>
