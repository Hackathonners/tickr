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
