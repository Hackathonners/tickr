<template>
  <div v-if="error" class="alert alert-danger">
    <ul>
      <li v-for="message in error.messages">{{ message }}</li>
    </ul>
  </div>
</template>
<script>
export default {
  props: ['error']
}
</script>

