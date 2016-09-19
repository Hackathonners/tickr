import Vue from 'vue'

Vue.directive('datepicker', {
  bind () {
    var vm = this.vm
    var key = this.expression
    $(this.el).datepicker({
      dateFormat: 'yy-mm-dd',
      altFormat: 'DD, d MM yy',
      altField: $(this.el).sibling,
      firstDay: 1,
      defaultDate: +1,
      minDate: 1,
      monthNames: ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'],
      dayNames: ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'],
      dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
      prevText: '<i class="fa fa-arrow-circle-left" aria-hidden="true"></i>',
      nextText: '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
      onSelect: function (date) {
        vm.$set(key, date)
      }
    })
  },
  update (newValue, oldValue) {
    $(this.el).datepicker('setDate', newValue)
  },
  unbind () {
    // do clean up work
    // e.g. remove event listeners added in bind()
  }
})
