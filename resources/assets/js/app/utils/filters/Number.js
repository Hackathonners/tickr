import Vue from 'vue';
import accounting from 'accounting';

Vue.filter('price', value => accounting.formatMoney(value, '€', 2, '.', ','));

Vue.filter('ratio', (value, decimals = 2, percent = true, separator = ',') => {
  let ratio = 0;

  if (!isNaN(value)) {
    ratio = value;
  }

  if (percent) {
    ratio *= 100;
  }

  return (`${ratio.toFixed(decimals)}%`).replace('.', separator);
});
