<template>
  <div v-if="error" class="alert alert-danger alert-disposable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <ul>
      <li v-for="message in error.messages">{{ message }}</li>
    </ul>
  </div>
</template>
<script>
  export default {
    props: ['error'],
  };
</script>

