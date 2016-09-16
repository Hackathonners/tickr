<template>
  <nav aria-label="...">
    <ul class="pager">
      <li :class="{ disabled: pagination.current_page == 1 }"><a href="#" @click.prevent="goToPage(pagination.current_page-1)">Previous</a></li>
      <li :class="{ disabled: pagination.current_page == pagination.total_pages }"><a href="#" @click.prevent="goToPage(pagination.current_page+1)">Next</a></li>
    </ul>
  </nav>
</template>
<script>
  export default {
    props: {
      pagination: {
        type: Object,
        required: true
      },
      callback: {
        type: Function,
        required: true
      },
    },
    methods: {
      goToPage(page = 1) {
        this.$set('pagination.current_page', page);
        this.callback(page);
      }
    }
  }
</script>
