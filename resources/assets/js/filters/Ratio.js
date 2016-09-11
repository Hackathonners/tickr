import Vue from 'vue';

Vue.filter('ratio', function (value, decimals = 2) {
  let ratio = 0;

  if(!isNaN(value))
    ratio = value;

  return '' + ratio.toFixed(decimals) + '%';
})
