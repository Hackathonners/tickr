<template>
  <nav>
    <ul v-show="pagination.total_pages > 1" class="pager">
      <li :class="{ disabled: pagination.current_page == 1 }">
        <a v-link="pagination.current_page > 1 ? { query: buildQuery(pagination.current_page - 1) } : null">Previous</a>
      </li>
      <li :class="{ disabled: pagination.current_page == pagination.total_pages }">
        <a v-link="pagination.current_page < pagination.total_pages ? { query: buildQuery(pagination.current_page + 1) } : null">Next</a>
      </li>
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
    }
  },
  methods: {
    buildQuery (page) {
      return Object.assign(this.$route.query, { page })
    },
    goToPage (page = 1) {
      this.$set('pagination.current_page', page)
      this.callback(page)
    }
  }
}
</script>
