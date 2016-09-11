import Vue from 'vue'

let url = 'events{/id}';

export default {

  /**
   * Get active events.
   */
  getActive() {
    return Vue.resource(url).get()
      .then((response) => Promise.resolve(response.json().data))
      .catch((error) => Promise.reject(error));
  },

  /**
   * Get past events.
   */
  getPast() {
    return Vue.resource(url, {filter: 'past'}).get()
      .then((response) => Promise.resolve(response.json().data))
      .catch((error) => Promise.reject(error));
  },

  /**
   * Get the event with given id.
   */
  get(id, stats = false) {
    stats = stats ? 1 : 0;
    return Vue.resource(url, {id: id, stats: stats}).get()
      .then((response) => Promise.resolve(response.json().data))
      .catch((error) => Promise.reject(error));
  },

}
