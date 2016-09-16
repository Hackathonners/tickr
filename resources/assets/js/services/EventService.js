import Vue from 'vue'

let url = 'events{/id}';

export default {

  /**
   * Get events listing.
   *
   * @param page
   * @param filter Filter for listing
   */
  list(page = 1, filter) {
    return Vue.resource(url, { page, filter }).get()
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error));
  },

  /**
   * Get a given event.
   *
   * @param id
   * @param stats Include event statistics
   */
  get(id, stats) {
    stats = !!stats ? 1 : 0;
    return Vue.resource(url, { id, stats }).get()
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error));
  },

}
