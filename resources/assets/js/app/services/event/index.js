import Vue from 'vue'

const url = 'events{/eventId}'

export default {
  /**
   * Get events listing.
   *
   * @param page
   * @param filter Filter for listing
   */
  list (page = 1, filter) {
    return Vue.resource(url, { page, filter }).get()
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error.body))
  },

  /**
   * Get a given event.
   *
   * @param eventId
   * @param stats Include event statistics
   */
  get (eventId, stats) {
    stats = stats ? 1 : 0
    return Vue.resource(url, { eventId, stats }).get()
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error.body))
  },

  /**
   * Store an event.
   *
   * @param data
   */
  store (data) {
    return Vue.resource(url).save(data)
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error.body))
  }
}
