import Vue from 'vue';

Vue.filter('ratio', function (value, decimals = 2, percent = true) {
  let ratio = 0;

  if(!isNaN(value))
    ratio = value;

  if(percent)
    ratio *= 100;

  return '' + ratio.toFixed(decimals) + '%';
})
