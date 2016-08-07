import Vue from 'vue';

Vue.directive('datepicker', {
  bind() {
    var vm = this.vm;
    var key = this.expression;
    $(this.el).datepicker({
      dateFormat: "yy-mm-dd",
      firstDay: 1,
      defaultDate: +1,
      minDate: 1,
      monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
      dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
      prevText: '<i class="fa fa-angle-left"></i>',
      nextText: '<i class="fa fa-angle-right"></i>',
      onSelect: function (date) {
        vm.$set(key, date);
      }
    });
  },
  update(newValue, oldValue) {
    $(this.el).datepicker('setDate', newValue);
  },
  unbind() {
    // do clean up work
    // e.g. remove event listeners added in bind()
  }
})
