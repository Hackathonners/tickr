import Vue from 'vue'

const url = 'events/{eventId}/registrations'

export default {
  /**
   * Get registrations listing.
   *
   * @param page
   * @param filter Limit
   */
  list (eventId, page, limit) {
    return Vue.resource(url, { eventId, page, limit }).get()
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error.body))
  },

  /**
   * Store a registration.
   *
   * @param eventId
   * @param data
   */
  store (eventId, data) {
    return Vue.resource(url, { eventId }).save(data)
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error.body))
  }
}
