/* ============
 * Paginator Component
 * ============
 *
 * Paginates data and set up links to previous and next pages.
 */

export default {
  props: {
    pagination: {
      type: Object,
      required: true,
    },
  },
  methods: {
    nextPage() {
      return this.buildQuery(this.pagination.current_page + 1);
    },
    prevPage() {
      return this.buildQuery(this.pagination.current_page - 1);
    },
    buildQuery(page) {
      return {
        query: Object.assign({}, this.$store.state.route.query, { page }),
      };
    },
  },
  computed: {
    singlePage() {
      return !this.pagination.total_pages || this.pagination.total_pages < 2;
    },
    onFirstPage() {
      return this.pagination.current_page === 1;
    },
    onLastPage() {
      return this.pagination.current_page === this.pagination.total_pages;
    },
  },
};
