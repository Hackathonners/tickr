import Vue from 'vue';
import moment from 'moment';

Vue.filter('date', (date, outFormat = "YYYY-MM-DD HH:mm:ss", inFormat = "YYYY-MM-DD HH:mm:ss") => {
  if(!moment(date).isValid())
    return date;

  return moment(date, inFormat).format(outFormat);
})

Vue.filter('human_diff', (date, suffix = false, inFormat = "YYYY-MM-DD HH:mm:ss") => {
  if(!moment(date).isValid())
    return date;

  return moment(date, inFormat).fromNow(suffix);
})

Vue.filter('date_range', (dateFrom, dateTo, format = "YYYY-MM-DD HH:mm:ss") => {
  let from = moment(dateFrom, format);
  let to = moment(dateTo, format);

  if(!from.isValid() || !to.isValid())
    return;

  if(to.isSame(from, 'day'))
    return from.format('dddd, D MMMM YYYY') +
      ' das ' +
      from.format('HH:mm') +
      ' às ' +
      to.format('HH:mm');

  let now = moment();
  let fromFormat = now.isSame(from, 'year') ? 'dddd, D MMMM' : 'dddd, D MMMM \\d\\e YYYY';
  let toFormat = now.isSame(to, 'year') ? 'dddd, D MMMM' : 'dddd, D MMMM \\d\\e YYYY';

  return from.format(fromFormat) +
      ' às ' +
      from.format('HH:mm') +
      ' - ' +
      to.format(toFormat) +
      ' às ' +
      to.format('HH:mm');

  return moment(date, inFormat).fromNow(suffix);
})
