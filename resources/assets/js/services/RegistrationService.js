import Vue from 'vue'

let url = 'events/{eventId}/registrations';

export default {

  /**
   * Store a registration.
   *
   * @param data
   */
  store(eventId, data) {
    return Vue.resource(url, { eventId }).save(data)
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error));
  },

}
