import Vue from 'vue';

Vue.filter('price', function (value, currency = true) {
  if(value == 0)
    return 'free'

  return this.$options.filters.currency(value, currency ? '€' : '');
})
