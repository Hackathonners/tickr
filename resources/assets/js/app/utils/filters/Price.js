import Vue from 'vue'

Vue.filter('price', function (value, currency = true) {
  return this.$options.filters.currency(value, currency ? '€' : '')
})
