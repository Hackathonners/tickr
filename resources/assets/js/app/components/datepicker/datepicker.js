/* ============
 * Paginator Component
 * ============
 *
 * Paginates data and set up links to previous and next pages.
 */
import Vue from 'vue'

export default {
  props: ['date'],

  mounted: function () {
    const vm = this;
    $(this.$el).datepicker({
      dateFormat: 'yy-mm-dd',
      altFormat: 'DD, d MM yy',
      firstDay: 1,
      defaultDate: +1,
      minDate: 1,
      monthNames: ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'],
      dayNames: ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'],
      dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
      prevText: '<i class="fa fa-arrow-circle-left" aria-hidden="true"></i>',
      nextText: '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
      onSelect: function (date) {
        vm.$emit('input', date)
      }
    })
  },

  watch: {
    date: function (date) {
      $(this.$el).datepicker('setDate', date)
    }
  },

  destroyed: function () {

  }
}
