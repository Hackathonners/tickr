import Vue from 'vue';

Vue.filter('price', function (value) {
  if(value == 0)
    return 'free'

  return this.$options.filters.currency(value, '€');
})
